<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductionJob;
use App\Models\Design;
use App\Models\Equipment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductionController extends Controller
{
    /**
     * Production Dashboard
     */
    public function dashboard()
    {
        // Production statistics
        $stats = [
            'pending_jobs' => ProductionJob::pending()->count(),
            'in_progress_jobs' => ProductionJob::inProgress()->count(),
            'completed_today' => ProductionJob::completed()
                ->whereDate('actual_end', today())
                ->count(),
            'overdue_jobs' => ProductionJob::where('status', 'pending')
                ->where('estimated_start', '<', now())
                ->count(),
        ];

        // Equipment status
        $equipmentStatus = [
            'active' => Equipment::active()->count(),
            'maintenance' => Equipment::maintenance()->count(),
            'needs_maintenance' => Equipment::needsMaintenance()->count(),
        ];

        // Recent jobs
        $recentJobs = ProductionJob::with(['order', 'product', 'assignedUser', 'equipment'])
            ->latest()
            ->limit(10)
            ->get();

        // Production efficiency (last 30 days)
        $efficiency = ProductionJob::completed()
            ->where('actual_end', '>=', now()->subDays(30))
            ->selectRaw('
                COUNT(*) as total_jobs,
                AVG(CASE WHEN actual_end <= estimated_start + INTERVAL estimated_duration MINUTE THEN 1 ELSE 0 END) * 100 as on_time_percentage,
                AVG((quantity - COALESCE(reject_quantity, 0)) / quantity * 100) as quality_percentage
            ')
            ->first();

        return view('admin.production.dashboard', compact(
            'stats',
            'equipmentStatus',
            'recentJobs',
            'efficiency'
        ));
    }

    /**
     * Production Jobs Management
     */
    public function jobs(Request $request)
    {
        $query = ProductionJob::with(['order', 'orderItem', 'product', 'design', 'assignedUser', 'equipment']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('equipment_id')) {
            $query->where('equipment_id', $request->equipment_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('job_number', 'like', "%{$search}%")
                  ->orWhereHas('order', function($orderQuery) use ($search) {
                      $orderQuery->where('order_number', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $jobs = $query->orderBy('priority', 'desc')
                     ->orderBy('estimated_start')
                     ->paginate(15)
                     ->withQueryString();

        // Get filter options
        $users = User::active()->get();
        $equipment = Equipment::active()->get();

        return view('admin.production.jobs.index', compact('jobs', 'users', 'equipment'));
    }

    /**
     * Create production job from order
     */
    public function createJobFromOrder(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_item_id' => 'required|exists:order_items,id',
            'design_id' => 'nullable|exists:designs,id',
            'priority' => 'required|in:low,normal,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'equipment_id' => 'nullable|exists:equipment,id',
            'estimated_start' => 'nullable|date',
            'estimated_duration' => 'nullable|integer|min:1',
            'production_notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $orderItem = OrderItem::findOrFail($validated['order_item_id']);

            $job = ProductionJob::create([
                'order_id' => $order->id,
                'order_item_id' => $orderItem->id,
                'product_id' => $orderItem->product_id,
                'design_id' => $validated['design_id'],
                'quantity' => $orderItem->quantity,
                'priority' => $validated['priority'],
                'assigned_to' => $validated['assigned_to'],
                'equipment_id' => $validated['equipment_id'],
                'estimated_start' => $validated['estimated_start'],
                'estimated_duration' => $validated['estimated_duration'],
                'production_notes' => $validated['production_notes'],
                'status' => 'pending',
            ]);

            DB::commit();

            return back()->with('success', "Job de produção {$job->job_number} criado com sucesso!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao criar job de produção: ' . $e->getMessage());
        }
    }

    /**
     * Start production job
     */
    public function startJob(ProductionJob $job)
    {
        if ($job->status !== 'pending') {
            return back()->with('error', 'Job não pode ser iniciado no status atual.');
        }

        try {
            $job->update([
                'status' => 'in_progress',
                'actual_start' => now(),
            ]);

            return back()->with('success', 'Produção iniciada!');

        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao iniciar produção: ' . $e->getMessage());
        }
    }

    /**
     * Complete production job
     */
    public function completeJob(Request $request, ProductionJob $job)
    {
        $validated = $request->validate([
            'temperature' => 'nullable|integer|min:0',
            'pressure' => 'nullable|numeric|min:0',
            'time_seconds' => 'nullable|integer|min:0',
            'reject_quantity' => 'nullable|integer|min:0|max:' . $job->quantity,
            'reject_reason' => 'nullable|string',
            'production_notes' => 'nullable|string',
            'materials_used' => 'nullable|array',
        ]);

        if ($job->status !== 'in_progress') {
            return back()->with('error', 'Job não pode ser finalizado no status atual.');
        }

        try {
            DB::beginTransaction();

            $job->update([
                'status' => 'quality_check',
                'actual_end' => now(),
                'temperature' => $validated['temperature'],
                'pressure' => $validated['pressure'],
                'time_seconds' => $validated['time_seconds'],
                'reject_quantity' => $validated['reject_quantity'] ?? 0,
                'reject_reason' => $validated['reject_reason'],
                'production_notes' => $validated['production_notes'],
                'materials_used' => $validated['materials_used'],
            ]);

            // Update equipment usage hours
            if ($job->equipment && $job->actual_start && $job->actual_end) {
                $usageMinutes = $job->actual_start->diffInMinutes($job->actual_end);
                $job->equipment->increment('usage_hours', round($usageMinutes / 60, 2));
            }

            DB::commit();

            return back()->with('success', 'Produção finalizada! Aguardando controle de qualidade.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao finalizar produção: ' . $e->getMessage());
        }
    }

    /**
     * Quality check
     */
    public function qualityCheck(Request $request, ProductionJob $job)
    {
        $validated = $request->validate([
            'quality_check_status' => 'required|in:approved,rejected',
            'quality_check_notes' => 'nullable|string',
        ]);

        if ($job->status !== 'quality_check') {
            return back()->with('error', 'Job não está em controle de qualidade.');
        }

        try {
            DB::beginTransaction();

            $job->update([
                'status' => $validated['quality_check_status'] === 'approved' ? 'completed' : 'pending',
                'quality_check_status' => $validated['quality_check_status'],
                'quality_check_notes' => $validated['quality_check_notes'],
                'quality_checked_by' => auth()->id(),
                'quality_checked_at' => now(),
            ]);

            // If approved, update product stock
            if ($validated['quality_check_status'] === 'approved') {
                $producedQuantity = $job->quantity - ($job->reject_quantity ?? 0);
                $job->product->increment('stock_quantity', $producedQuantity);
            }

            DB::commit();

            $message = $validated['quality_check_status'] === 'approved' 
                ? 'Produção aprovada e estoque atualizado!' 
                : 'Produção rejeitada. Job retornado para produção.';

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro no controle de qualidade: ' . $e->getMessage());
        }
    }

    /**
     * Designs Management
     */
    public function designs(Request $request)
    {
        $query = Design::with(['category', 'creator', 'approver']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('is_template')) {
            $query->where('is_template', $request->is_template);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $designs = $query->orderBy('created_at', 'desc')
                        ->paginate(15)
                        ->withQueryString();

        return view('admin.production.designs.index', compact('designs'));
    }

    /**
     * Upload new design
     */
    public function uploadDesign(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf,ai,psd,svg|max:10240',
            'is_template' => 'boolean',
            'is_public' => 'boolean',
            'tags' => 'nullable|array',
            'dpi' => 'nullable|integer|min:72',
            'color_profile' => 'nullable|string|max:50',
        ]);

        try {
            DB::beginTransaction();

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('designs', $fileName, 'public');

            $design = Design::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'category_id' => $validated['category_id'],
                'file_path' => $filePath,
                'file_name' => $fileName,
                'file_size' => $file->getSize(),
                'file_type' => $file->getClientMimeType(),
                'dpi' => $validated['dpi'],
                'color_profile' => $validated['color_profile'],
                'is_template' => $validated['is_template'] ?? false,
                'is_public' => $validated['is_public'] ?? false,
                'tags' => $validated['tags'],
                'created_by' => auth()->id(),
                'status' => 'draft',
            ]);

            DB::commit();

            return back()->with('success', 'Design enviado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao enviar design: ' . $e->getMessage());
        }
    }

    /**
     * Equipment Management
     */
    public function equipment(Request $request)
    {
        $query = Equipment::query();

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        $equipment = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.production.equipment.index', compact('equipment'));
    }

    /**
     * Store new equipment
     */
    public function storeEquipment(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:printer,heat_press,cutter,laminator,other',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'warranty_until' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'max_width' => 'nullable|integer|min:1',
            'max_height' => 'nullable|integer|min:1',
            'max_temperature' => 'nullable|integer|min:0',
            'min_temperature' => 'nullable|integer|min:0',
            'max_pressure' => 'nullable|numeric|min:0',
            'capabilities' => 'nullable|array',
            'maintenance_schedule' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            Equipment::create($validated);

            return back()->with('success', 'Equipamento cadastrado com sucesso!');

        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao cadastrar equipamento: ' . $e->getMessage());
        }
    }
}
