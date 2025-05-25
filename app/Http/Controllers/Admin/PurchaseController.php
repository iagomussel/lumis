<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Supplier;

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
        // Assuming Product model for item selection
        $products = \App\Models\Product::where('status', 'active')->orderBy('name')->get(['id', 'name', 'sku', 'cost_price']);
        $statuses = Purchase::getPossibleStatuses();
        // Generate a unique purchase number (example)
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
            'status' => 'required|string', // Add specific status validation based on Purchase model
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
            // 'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
            // 'items.*.discount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            // 'shipping' => 'nullable|numeric|min:0',
            // 'payment_method' => 'required|string'
        ]);

        // Complex logic for creating purchase and purchase items
        // This often involves a database transaction

        // DB::transaction(function () use ($request) {
        //     $purchase = Purchase::create([
        //         'supplier_id' => $request->supplier_id,
        //         'purchase_number' => $request->purchase_number,
        //         'delivery_date' => $request->delivery_date,
        //         'status' => $request->status,
        //         'notes' => $request->notes,
        //         'total' => $request->total,
        //         // ... other fields ...
        //     ]);

        //     foreach ($request->items as $itemData) {
        //         $purchase->items()->create([
        //             'product_id' => $itemData['product_id'],
        //             'quantity' => $itemData['quantity'],
        //             'unit_cost' => $itemData['unit_cost'],
        //             'sub_total' => $itemData['quantity'] * $itemData['unit_cost'],
        //             // ... other item fields ...
        //         ]);
        //     }
        // });
        
        // For now, a placeholder response. Full implementation of store is complex.
        // Purchase::create($request->all()); // Simplified, needs proper item handling

        return redirect()->route('admin.purchases.index')
                         ->with('success', 'Compra registrada com sucesso! (Itens precisam ser implementados)');
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        $purchase->load('supplier', 'items.product'); // Eager load related data
        return view('admin.purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $purchase->load('items'); // Load items for editing
        $suppliers = Supplier::where('status', 'active')->orderBy('name')->pluck('name', 'id');
        $products = \App\Models\Product::where('status', 'active')->orderBy('name')->get(['id', 'name', 'sku', 'cost_price']);
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
            'status' => 'required|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ]);

        // Complex logic for updating purchase and its items (add, remove, update items)
        // This often involves a database transaction and careful handling of item changes.

        // DB::transaction(function () use ($request, $purchase) {
        //    $purchase->update($request->except('items')); 
        //    // Logic to sync items: delete old, update existing, add new
        // });

        // For now, a placeholder response.
        // $purchase->update($request->all()); // Simplified

        return redirect()->route('admin.purchases.index')
                         ->with('success', 'Compra atualizada com sucesso! (Itens precisam ser implementados)');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase) // Route model binding
    {
        // Check if purchase can be deleted (e.g., not received yet)
        if ($purchase->status === 'received') {
            return redirect()->route('admin.purchases.index')
                           ->with('error', 'Não é possível excluir uma compra já recebida.');
        }

        $purchase->delete();

        return redirect()->route('admin.purchases.index')
                         ->with('success', 'Compra excluída com sucesso!');
    }
}
