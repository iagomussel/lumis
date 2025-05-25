<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Purchase::with('supplier');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('purchase_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($supplierQuery) use ($search) {
                      $supplierQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $purchases = $query->latest()->paginate(20)->withQueryString();
        $suppliers = Supplier::orderBy('name')->pluck('name', 'id');
        $statuses = Purchase::getPossibleStatuses();

        return view('admin.purchases.index', compact('purchases', 'suppliers', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::where('status', 'active')->orderBy('name')->pluck('name', 'id');
        $products = Product::where('status', 'active')->orderBy('name')->get(['id', 'name', 'sku', 'cost_price']);
        $statuses = Purchase::getPossibleStatuses();
        // Generate a unique purchase number
        $purchase_number = 'COM-' . date('Y') . '-' . str_pad((Purchase::count() + 1), 6, '0', STR_PAD_LEFT);

        return view('admin.purchases.create', compact('suppliers', 'products', 'statuses', 'purchase_number'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_number' => 'required|string|max:255|unique:purchases,purchase_number',
            'delivery_date' => 'nullable|date',
            'status' => 'required|string|in:pending,ordered,received,cancelled',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'payment_terms' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'shipping' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Calculate totals
                $subtotal = 0;
                foreach ($request->items as $item) {
                    $subtotal += $item['quantity'] * $item['unit_cost'];
                }
                
                $discount = $request->discount ?? 0;
                $tax = $request->tax ?? 0;
                $shipping = $request->shipping ?? 0;
                $total = $subtotal - $discount + $tax + $shipping;

                $purchase = Purchase::create([
                    'supplier_id' => $request->supplier_id,
                    'user_id' => Auth::id(),
                    'purchase_number' => $request->purchase_number,
                    'type' => 'purchase_order',
                    'delivery_date' => $request->delivery_date,
                    'status' => $request->status,
                    'notes' => $request->notes,
                    'payment_terms' => $request->payment_terms,
                    'payment_method' => $request->payment_method,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'tax' => $tax,
                    'shipping' => $shipping,
                    'total' => $total,
                ]);

                foreach ($request->items as $itemData) {
                    $product = Product::find($itemData['product_id']);
                    
                    $purchase->items()->create([
                        'product_id' => $itemData['product_id'],
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'quantity_ordered' => $itemData['quantity'],
                        'quantity_received' => 0,
                        'unit_price' => $itemData['unit_cost'],
                        'total_price' => $itemData['quantity'] * $itemData['unit_cost'],
                        'notes' => $itemData['notes'] ?? null,
                    ]);
                }

                // If status is 'received', update inventory
                if ($request->status === 'received') {
                    $this->updateInventoryOnReceive($purchase);
                }
            });

            return redirect()->route('admin.purchases.index')
                           ->with('success', 'Compra registrada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Erro ao registrar compra: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        $purchase->load('supplier', 'items.product', 'user');
        return view('admin.purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $purchase->load('items');
        $suppliers = Supplier::where('status', 'active')->orderBy('name')->pluck('name', 'id');
        $products = Product::where('status', 'active')->orderBy('name')->get(['id', 'name', 'sku', 'cost_price']);
        $statuses = Purchase::getPossibleStatuses();

        return view('admin.purchases.edit', compact('purchase', 'suppliers', 'products', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_number' => 'required|string|max:255|unique:purchases,purchase_number,' . $purchase->id,
            'delivery_date' => 'nullable|date',
            'status' => 'required|string|in:pending,ordered,received,cancelled',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'payment_terms' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'shipping' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request, $purchase) {
                $originalStatus = $purchase->status;
                
                // Calculate totals
                $subtotal = 0;
                foreach ($request->items as $item) {
                    $subtotal += $item['quantity'] * $item['unit_cost'];
                }
                
                $discount = $request->discount ?? 0;
                $tax = $request->tax ?? 0;
                $shipping = $request->shipping ?? 0;
                $total = $subtotal - $discount + $tax + $shipping;

                $purchase->update([
                    'supplier_id' => $request->supplier_id,
                    'purchase_number' => $request->purchase_number,
                    'delivery_date' => $request->delivery_date,
                    'status' => $request->status,
                    'notes' => $request->notes,
                    'payment_terms' => $request->payment_terms,
                    'payment_method' => $request->payment_method,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'tax' => $tax,
                    'shipping' => $shipping,
                    'total' => $total,
                ]);

                // Update items - delete existing and recreate
                $purchase->items()->delete();
                
                foreach ($request->items as $itemData) {
                    $product = Product::find($itemData['product_id']);
                    
                    $purchase->items()->create([
                        'product_id' => $itemData['product_id'],
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'quantity_ordered' => $itemData['quantity'],
                        'quantity_received' => 0,
                        'unit_price' => $itemData['unit_cost'],
                        'total_price' => $itemData['quantity'] * $itemData['unit_cost'],
                        'notes' => $itemData['notes'] ?? null,
                    ]);
                }

                // Handle status change to 'received'
                if ($originalStatus !== 'received' && $request->status === 'received') {
                    $this->updateInventoryOnReceive($purchase);
                    $purchase->update(['received_at' => now()]);
                }
            });

            return redirect()->route('admin.purchases.index')
                           ->with('success', 'Compra atualizada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Erro ao atualizar compra: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        // Check if purchase can be deleted
        if ($purchase->status === 'received') {
            return redirect()->route('admin.purchases.index')
                           ->with('error', 'Não é possível excluir uma compra já recebida.');
        }

        try {
            DB::transaction(function () use ($purchase) {
                $purchase->items()->delete();
                $purchase->delete();
            });

            return redirect()->route('admin.purchases.index')
                           ->with('success', 'Compra excluída com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('admin.purchases.index')
                           ->with('error', 'Erro ao excluir compra: ' . $e->getMessage());
        }
    }

    /**
     * Update inventory when purchase is received
     */
    private function updateInventoryOnReceive(Purchase $purchase)
    {
        foreach ($purchase->items as $item) {
            $product = $item->product;
            if ($product) {
                // Update stock quantity
                $product->increment('stock_quantity', $item->quantity_ordered);
                
                // Update cost price if different
                if ($product->cost_price != $item->unit_price) {
                    $product->update(['cost_price' => $item->unit_price]);
                }
                
                // Mark item as received
                $item->update(['quantity_received' => $item->quantity_ordered]);
            }
        }
    }

    /**
     * Mark purchase as received and update inventory
     */
    public function markAsReceived(Purchase $purchase)
    {
        if ($purchase->status === 'received') {
            return redirect()->back()->with('error', 'Compra já foi marcada como recebida.');
        }

        try {
            DB::transaction(function () use ($purchase) {
                $purchase->update([
                    'status' => 'received',
                    'received_at' => now()
                ]);
                
                $this->updateInventoryOnReceive($purchase);
            });

            return redirect()->back()->with('success', 'Compra marcada como recebida e estoque atualizado!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao marcar compra como recebida: ' . $e->getMessage());
        }
    }
}
