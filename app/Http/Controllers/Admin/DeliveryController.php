<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliverySchedule;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DeliveryController extends Controller
{
    public function index(Request $request)
    {
        $query = DeliverySchedule::with(['order', 'customer']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->where('scheduled_date', $request->date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tracking_code', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('city')) {
            $query->where('delivery_city', 'like', "%{$request->city}%");
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'scheduled_date');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        $deliveries = $query->paginate(15)->withQueryString();

        // Statistics
        $stats = [
            'total' => DeliverySchedule::count(),
            'today' => DeliverySchedule::scheduledForToday()->count(),
            'pending' => DeliverySchedule::pending()->count(),
            'in_progress' => DeliverySchedule::inProgress()->count(),
            'completed_today' => DeliverySchedule::scheduledForToday()->completed()->count(),
            'overdue' => DeliverySchedule::where('scheduled_date', '<', Carbon::today())
                                       ->whereNotIn('status', ['delivered', 'cancelled'])
                                       ->count(),
        ];

        // Financial stats
        $financialStats = [
            'total_amount' => DeliverySchedule::sum('total_amount'),
            'paid_amount' => DeliverySchedule::sum('paid_amount'),
            'remaining_amount' => DeliverySchedule::sum('remaining_amount'),
        ];

        return view('admin.deliveries.index', compact('deliveries', 'stats', 'financialStats'));
    }

    public function create()
    {
        $orders = Order::whereDoesntHave('deliverySchedule')
                      ->with('customer')
                      ->where('status', 'completed')
                      ->get();
        
        $customers = Customer::all();
        
        return view('admin.deliveries.create', compact('orders', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'scheduled_time_start' => 'nullable|date_format:H:i',
            'scheduled_time_end' => 'nullable|date_format:H:i|after:scheduled_time_start',
            'delivery_address' => 'required|string|max:255',
            'delivery_city' => 'required|string|max:100',
            'delivery_state' => 'required|string|max:2',
            'delivery_zip_code' => 'required|string|max:10',
            'delivery_notes' => 'nullable|string',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0|lte:total_amount',
        ]);

        try {
            DB::beginTransaction();

            $order = Order::findOrFail($request->order_id);
            
            $delivery = new DeliverySchedule($request->all());
            $delivery->customer_id = $order->customer_id;
            $delivery->remaining_amount = $delivery->total_amount - $delivery->paid_amount;
            $delivery->generateTrackingCode();
            $delivery->save();

            // Update order status if needed
            if ($order->status !== 'scheduled_for_delivery') {
                $order->update(['status' => 'scheduled_for_delivery']);
            }

            DB::commit();

            return redirect()->route('admin.deliveries.index')
                           ->with('success', 'Entrega agendada com sucesso! Código de rastreamento: ' . $delivery->tracking_code);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erro ao agendar entrega: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    public function show(DeliverySchedule $delivery)
    {
        $delivery->load(['order.items.product', 'customer']);
        
        return view('admin.deliveries.show', compact('delivery'));
    }

    public function edit(DeliverySchedule $delivery)
    {
        $orders = Order::with('customer')->get();
        $customers = Customer::all();
        
        return view('admin.deliveries.edit', compact('delivery', 'orders', 'customers'));
    }

    public function update(Request $request, DeliverySchedule $delivery)
    {
        $request->validate([
            'scheduled_date' => 'required|date',
            'scheduled_time_start' => 'nullable|date_format:H:i',
            'scheduled_time_end' => 'nullable|date_format:H:i|after:scheduled_time_start',
            'delivery_address' => 'required|string|max:255',
            'delivery_city' => 'required|string|max:100',
            'delivery_state' => 'required|string|max:2',
            'delivery_zip_code' => 'required|string|max:10',
            'delivery_notes' => 'nullable|string',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0|lte:total_amount',
            'driver_name' => 'nullable|string|max:100',
            'driver_phone' => 'nullable|string|max:20',
            'vehicle_info' => 'nullable|string|max:100',
        ]);

        try {
            $delivery->update($request->all());
            $delivery->remaining_amount = $delivery->total_amount - $delivery->paid_amount;
            $delivery->save();

            return redirect()->route('admin.deliveries.index')
                           ->with('success', 'Entrega atualizada com sucesso!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao atualizar entrega: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    public function destroy(DeliverySchedule $delivery)
    {
        try {
            if (in_array($delivery->status, ['in_transit', 'delivered'])) {
                return back()->withErrors(['error' => 'Não é possível excluir entregas em trânsito ou já entregues.']);
            }

            $delivery->delete();

            return redirect()->route('admin.deliveries.index')
                           ->with('success', 'Entrega excluída com sucesso!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao excluir entrega: ' . $e->getMessage()]);
        }
    }

    public function confirm(DeliverySchedule $delivery)
    {
        try {
            if ($delivery->status !== 'scheduled') {
                return back()->withErrors(['error' => 'Apenas entregas agendadas podem ser confirmadas.']);
            }

            $delivery->confirm();

            return back()->with('success', 'Entrega confirmada com sucesso!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao confirmar entrega: ' . $e->getMessage()]);
        }
    }

    public function dispatch(Request $request, DeliverySchedule $delivery)
    {
        $request->validate([
            'driver_name' => 'required|string|max:100',
            'driver_phone' => 'nullable|string|max:20',
            'vehicle_info' => 'nullable|string|max:100',
        ]);

        try {
            if (!in_array($delivery->status, ['scheduled', 'confirmed'])) {
                return back()->withErrors(['error' => 'Apenas entregas agendadas ou confirmadas podem ser despachadas.']);
            }

            $delivery->dispatch(
                $request->driver_name,
                $request->driver_phone,
                $request->vehicle_info
            );

            return back()->with('success', 'Entrega despachada com sucesso!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao despachar entrega: ' . $e->getMessage()]);
        }
    }

    public function markAsDelivered(Request $request, DeliverySchedule $delivery)
    {
        $request->validate([
            'delivery_proof' => 'nullable|string',
        ]);

        try {
            if ($delivery->status !== 'in_transit') {
                return back()->withErrors(['error' => 'Apenas entregas em trânsito podem ser marcadas como entregues.']);
            }

            $delivery->markAsDelivered($request->delivery_proof);

            // Update order status
            $delivery->order->update(['status' => 'delivered']);

            return back()->with('success', 'Entrega marcada como realizada com sucesso!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao marcar entrega como realizada: ' . $e->getMessage()]);
        }
    }

    public function cancel(DeliverySchedule $delivery)
    {
        try {
            if (in_array($delivery->status, ['delivered', 'cancelled'])) {
                return back()->withErrors(['error' => 'Não é possível cancelar entregas já entregues ou canceladas.']);
            }

            $delivery->cancel();

            return back()->with('success', 'Entrega cancelada com sucesso!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao cancelar entrega: ' . $e->getMessage()]);
        }
    }

    public function updatePayment(Request $request, DeliverySchedule $delivery)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:0|max:' . $delivery->total_amount,
        ]);

        try {
            $delivery->updatePayment($request->paid_amount);

            return back()->with('success', 'Pagamento atualizado com sucesso!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao atualizar pagamento: ' . $e->getMessage()]);
        }
    }

    public function track($trackingCode)
    {
        $delivery = DeliverySchedule::where('tracking_code', $trackingCode)
                                   ->with(['order', 'customer'])
                                   ->firstOrFail();

        return view('admin.deliveries.track', compact('delivery'));
    }

    public function dashboard()
    {
        $todayStats = DeliverySchedule::getTodayStats();
        
        $recentDeliveries = DeliverySchedule::with(['order', 'customer'])
                                          ->orderBy('created_at', 'desc')
                                          ->limit(10)
                                          ->get();

        $overdueDeliveries = DeliverySchedule::with(['order', 'customer'])
                                           ->where('scheduled_date', '<', Carbon::today())
                                           ->whereNotIn('status', ['delivered', 'cancelled'])
                                           ->get();

        return view('admin.deliveries.dashboard', compact('todayStats', 'recentDeliveries', 'overdueDeliveries'));
    }
} 