<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'low':
                    $query->where('stock_quantity', '<=', 10);
                    break;
                case 'out':
                    $query->where('stock_quantity', '<=', 0);
                    break;
                case 'available':
                    $query->where('stock_quantity', '>', 10);
                    break;
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Ordenação
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        if (in_array($sortBy, ['name', 'sku', 'stock_quantity', 'price', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('name', 'asc');
        }

        $products = $query->paginate(20);
        $categories = Category::all();

        // Estatísticas
        $stats = [
            'total_products' => Product::count(),
            'low_stock' => Product::where('stock_quantity', '<=', 10)->count(),
            'out_of_stock' => Product::where('stock_quantity', '<=', 0)->count(),
            'total_value' => Product::sum(DB::raw('stock_quantity * cost_price')),
        ];

        return view('admin.inventory.index', compact('products', 'categories', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Para estoque, geralmente não criamos novos itens aqui
        // Redirecionamos para criar produto
        return redirect()->route('admin.products.create')
            ->with('info', 'Para adicionar novos itens ao estoque, primeiro crie o produto.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Não implementado - estoque é gerenciado através de ajustes
        return redirect()->route('admin.inventory.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $inventory)
    {
        $product = $inventory->load('category');
        
        // Aqui poderia ter histórico de movimentações se implementado
        // $movements = StockMovement::where('product_id', $product->id)->latest()->paginate(10);
        
        return view('admin.inventory.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $inventory)
    {
        $product = $inventory->load('category');
        return view('admin.inventory.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $inventory)
    {
        $validated = $request->validate([
            'stock_quantity' => 'required|integer|min:0',
            'adjustment_type' => 'required|in:set,add,subtract',
            'adjustment_reason' => 'nullable|string|max:500',
            'cost_price' => 'nullable|numeric|min:0',
        ]);

        $product = $inventory;
        $oldStock = $product->stock_quantity;
        
        // Aplicar ajuste baseado no tipo
        switch ($validated['adjustment_type']) {
            case 'set':
                $newStock = $validated['stock_quantity'];
                break;
            case 'add':
                $newStock = $oldStock + $validated['stock_quantity'];
                break;
            case 'subtract':
                $newStock = max(0, $oldStock - $validated['stock_quantity']);
                break;
        }

        $product->stock_quantity = $newStock;
        
        if (isset($validated['cost_price'])) {
            $product->cost_price = $validated['cost_price'];
        }
        
        $product->save();

        // Aqui poderia registrar o movimento no histórico
        // StockMovement::create([
        //     'product_id' => $product->id,
        //     'type' => $validated['adjustment_type'],
        //     'quantity' => $validated['stock_quantity'],
        //     'old_stock' => $oldStock,
        //     'new_stock' => $newStock,
        //     'reason' => $validated['adjustment_reason'],
        //     'user_id' => auth()->id(),
        // ]);

        $message = "Estoque atualizado: {$product->name} - {$oldStock} → {$newStock} unidades";
        
        return redirect()->route('admin.inventory.index')
            ->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $inventory)
    {
        // Estoque não é deletado, apenas ajustado
        return redirect()->route('admin.inventory.index')
            ->with('info', 'Para remover itens do estoque, use a função de ajuste.');
    }

    /**
     * Bulk update stock quantities
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.stock_quantity' => 'required|integer|min:0',
        ]);

        $updated = 0;
        foreach ($validated['products'] as $productData) {
            $product = Product::find($productData['id']);
            if ($product) {
                $product->stock_quantity = $productData['stock_quantity'];
                $product->save();
                $updated++;
            }
        }

        return redirect()->route('admin.inventory.index')
            ->with('success', "Estoque atualizado para {$updated} produto(s).");
    }

    /**
     * Export inventory report
     */
    public function export(Request $request)
    {
        // Implementar exportação se necessário
        return redirect()->route('admin.inventory.index')
            ->with('info', 'Funcionalidade de exportação será implementada em breve.');
    }
} 