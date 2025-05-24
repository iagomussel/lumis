<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'user', 'items']);

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();
        $customers = Customer::active()->orderBy('name')->get();

        // Estatísticas
        $stats = [
            'total_orders' => $query->count(),
            'total_revenue' => $query->sum('total'),
            'pending_orders' => Order::pending()->count(),
            'confirmed_orders' => Order::confirmed()->count(),
        ];

        return view('admin.orders.index', compact('orders', 'customers', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['customer', 'user', 'items.product']);
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $order->load(['customer', 'items.product']);
        $customers = Customer::active()->orderBy('name')->get();
        
        return view('admin.orders.edit', compact('order', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,refunded,cancelled',
            'payment_method' => 'required|in:cash,card,pix,bank_transfer',
            'discount' => 'nullable|numeric|min:0',
            'shipping' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'shipping_address' => 'nullable|string|max:255',
            'shipping_number' => 'nullable|string|max:20',
            'shipping_complement' => 'nullable|string|max:100',
            'shipping_neighborhood' => 'nullable|string|max:100',
            'shipping_city' => 'nullable|string|max:100',
            'shipping_state' => 'nullable|string|max:2',
            'shipping_zip_code' => 'nullable|string|max:10',
        ]);

        $data = $request->only([
            'status', 'payment_status', 'payment_method', 'discount', 'shipping', 'notes',
            'shipping_address', 'shipping_number', 'shipping_complement', 
            'shipping_neighborhood', 'shipping_city', 'shipping_state', 'shipping_zip_code'
        ]);

        // Recalcular total se desconto ou frete foram alterados
        $discount = $request->discount ?? 0;
        $shipping = $request->shipping ?? 0;
        $data['total'] = $order->subtotal - $discount + $shipping;

        // Definir datas baseadas no status
        if ($request->status === 'shipped' && !$order->shipped_at) {
            $data['shipped_at'] = now();
        }

        if ($request->status === 'delivered' && !$order->delivered_at) {
            $data['delivered_at'] = now();
        }

        $order->update($data);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pedido atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        // Apenas permitir exclusão de pedidos cancelados ou pendentes
        if (!in_array($order->status, ['pending', 'cancelled'])) {
            return redirect()->route('admin.orders.index')
                ->with('error', 'Não é possível excluir pedidos confirmados ou processados.');
        }

        // Restaurar estoque dos produtos
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock_quantity', $item->quantity);
            }
        }

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Pedido excluído com sucesso!');
    }

    /**
     * Cancel order
     */
    public function cancel(Order $order)
    {
        if (in_array($order->status, ['delivered', 'cancelled'])) {
            return redirect()->back()
                ->with('error', 'Não é possível cancelar este pedido.');
        }

        // Restaurar estoque se o pedido estava confirmado
        if ($order->status === 'confirmed') {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock_quantity', $item->quantity);
                }
            }
        }

        $order->update([
            'status' => 'cancelled',
            'payment_status' => 'cancelled',
        ]);

        return redirect()->back()
            ->with('success', 'Pedido cancelado com sucesso!');
    }

    /**
     * Mark as paid
     */
    public function markAsPaid(Order $order)
    {
        $order->update(['payment_status' => 'paid']);

        return redirect()->back()
            ->with('success', 'Pedido marcado como pago!');
    }

    /**
     * Print order
     */
    public function print(Order $order)
    {
        $order->load(['customer', 'items.product', 'user']);
        
        return view('admin.orders.print', compact('order'));
    }
}
