<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with(['user']);

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('description', 'like', "%{$searchTerm}%")
                  ->orWhere('user_name', 'like', "%{$searchTerm}%")
                  ->orWhere('user_email', 'like', "%{$searchTerm}%")
                  ->orWhere('action', 'like', "%{$searchTerm}%")
                  ->orWhere('model_name', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by severity
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('performed_at', '>=', Carbon::parse($request->date_from)->startOfDay());
        }

        if ($request->filled('date_to')) {
            $query->where('performed_at', '<=', Carbon::parse($request->date_to)->endOfDay());
        }

        // Quick date filters
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('performed_at', today());
                    break;
                case 'yesterday':
                    $query->whereDate('performed_at', yesterday());
                    break;
                case 'this_week':
                    $query->whereBetween('performed_at', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'last_week':
                    $query->whereBetween('performed_at', [
                        Carbon::now()->subWeek()->startOfWeek(),
                        Carbon::now()->subWeek()->endOfWeek()
                    ]);
                    break;
                case 'this_month':
                    $query->whereMonth('performed_at', now()->month)
                          ->whereYear('performed_at', now()->year);
                    break;
                case 'last_month':
                    $query->whereMonth('performed_at', now()->subMonth()->month)
                          ->whereYear('performed_at', now()->subMonth()->year);
                    break;
            }
        }

        // Get filter options for dropdowns
        $users = User::select('id', 'name', 'email')->orderBy('name')->get();
        $actions = ActivityLog::select('action')->distinct()->orderBy('action')->pluck('action');
        $categories = ActivityLog::select('category')->distinct()->orderBy('category')->pluck('category');
        $modelTypes = ActivityLog::select('model_type')->distinct()->whereNotNull('model_type')->orderBy('model_type')->pluck('model_type');
        $severities = ['info', 'warning', 'error', 'critical'];

        // Get statistics
        $stats = [
            'total_logs' => ActivityLog::count(),
            'today_logs' => ActivityLog::whereDate('performed_at', today())->count(),
            'critical_logs' => ActivityLog::where('severity', 'critical')->count(),
            'error_logs' => ActivityLog::where('severity', 'error')->count(),
        ];

        // Order by most recent and paginate
        $logs = $query->orderBy('performed_at', 'desc')
                     ->paginate(50)
                     ->withQueryString();

        return view('admin.activity-logs.index', compact(
            'logs', 'users', 'actions', 'categories', 'modelTypes', 'severities', 'stats'
        ));
    }

    public function show(ActivityLog $activityLog)
    {
        $activityLog->load(['user']);

        return view('admin.activity-logs.show', compact('activityLog'));
    }

    public function destroy(ActivityLog $activityLog)
    {
        $activityLog->delete();

        return redirect()->route('admin.activity-logs.index')
                        ->with('success', 'Log de atividade excluído com sucesso.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:activity_logs,id'
        ]);

        ActivityLog::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' logs excluídos com sucesso.'
        ]);
    }

    public function cleanup(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);

        $cutoffDate = Carbon::now()->subDays($request->days);
        $deletedCount = ActivityLog::where('performed_at', '<', $cutoffDate)->delete();

        return redirect()->route('admin.activity-logs.index')
                        ->with('success', "Limpeza concluída: {$deletedCount} logs antigos foram removidos.");
    }

    public function export(Request $request)
    {
        // This method could be extended to export logs as CSV/Excel
        $query = ActivityLog::with(['user']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('description', 'like', "%{$searchTerm}%")
                  ->orWhere('user_name', 'like', "%{$searchTerm}%")
                  ->orWhere('action', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->filled('date_from')) {
            $query->where('performed_at', '>=', Carbon::parse($request->date_from)->startOfDay());
        }

        if ($request->filled('date_to')) {
            $query->where('performed_at', '<=', Carbon::parse($request->date_to)->endOfDay());
        }

        $logs = $query->orderBy('performed_at', 'desc')->get();

        $csv = "Data,Usuário,Ação,Descrição,Categoria,Severidade,IP,URL\n";
        
        foreach ($logs as $log) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s,%s\n",
                $log->performed_at->format('d/m/Y H:i:s'),
                $log->user_name ?? 'Sistema',
                $log->action,
                str_replace(['"', ','], ['""', ';'], $log->description),
                $log->category,
                $log->severity,
                $log->ip_address,
                str_replace(['"', ','], ['""', ';'], $log->url)
            );
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="activity_logs_' . now()->format('Y-m-d_H-i') . '.csv"');
    }

    public function dashboard()
    {
        // Activity overview for the last 30 days
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        $stats = [
            'total_activities' => ActivityLog::where('performed_at', '>=', $thirtyDaysAgo)->count(),
            'unique_users' => ActivityLog::where('performed_at', '>=', $thirtyDaysAgo)->distinct('user_id')->count('user_id'),
            'critical_alerts' => ActivityLog::where('severity', 'critical')->where('performed_at', '>=', $thirtyDaysAgo)->count(),
            'error_count' => ActivityLog::where('severity', 'error')->where('performed_at', '>=', $thirtyDaysAgo)->count(),
        ];

        // Daily activity chart data (last 7 days)
        $dailyActivity = ActivityLog::select(
                DB::raw('DATE(performed_at) as date'),
                DB::raw('COUNT(*) as count'),
                'severity'
            )
            ->where('performed_at', '>=', Carbon::now()->subDays(7))
            ->groupBy(DB::raw('DATE(performed_at)'), 'severity')
            ->orderBy('date')
            ->get()
            ->groupBy('date');

        // Top actions
        $topActions = ActivityLog::select('action', DB::raw('COUNT(*) as count'))
            ->where('performed_at', '>=', $thirtyDaysAgo)
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // Most active users
        $topUsers = ActivityLog::select('user_name', 'user_email', DB::raw('COUNT(*) as count'))
            ->where('performed_at', '>=', $thirtyDaysAgo)
            ->whereNotNull('user_name')
            ->groupBy('user_name', 'user_email')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // Recent critical/error logs
        $recentAlerts = ActivityLog::with(['user'])
            ->whereIn('severity', ['critical', 'error'])
            ->orderBy('performed_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.activity-logs.dashboard', compact(
            'stats', 'dailyActivity', 'topActions', 'topUsers', 'recentAlerts'
        ));
    }
} 