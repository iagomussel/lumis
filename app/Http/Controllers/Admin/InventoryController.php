<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product; // Assuming Product model exists and has stock_quantity
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch products with their stock quantities
        // For now, let's assume all products are fetched.
        // Pagination should be added later for performance.
        $products = Product::select('id', 'name', 'sku', 'stock_quantity', 'price', 'cost_price')
                            ->orderBy('name')
                            ->paginate(25); // Paginate results

        return view('admin.inventory.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not implemented yet
        return view('admin.inventory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Not implemented yet
        // Logic to add new stock items or initial stock for new products
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not implemented yet
        // Might show history of stock movements for a product
    }

    /**
     * Show the form for editing the specified resource.
     * This will likely be to adjust stock for a specific product.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return view('admin.inventory.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     * This will be for updating stock quantity.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
            // Potentially add a field for 'adjustment_reason' or 'notes'
        ]);

        $product = Product::findOrFail($id);
        $product->stock_quantity = $request->stock_quantity;
        $product->save();

        // Add activity log here if implemented

        return redirect()->route('admin.inventory.index')->with('success', 'Estoque atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Generally, stock records are not destroyed but adjusted.
        // This method might not be applicable or used for different purpose.
    }

    // Potential additional methods:
    // - public function stockMovements(Product $product) -> view for stock history
    // - public function recordAdjustment(Request $request, Product $product) -> for + or - adjustments
} 