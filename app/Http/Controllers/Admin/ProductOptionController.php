<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductOption;
use Illuminate\Http\Request;

class ProductOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProductOption::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('active')) {
            $query->where('active', $request->boolean('active'));
        }

        $options = $query->ordered()->paginate(15)->withQueryString();

        return view('admin.product-options.index', compact('options'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.product-options.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:select,color,text,number',
            'description' => 'nullable|string',
            'values' => 'required|array|min:1',
            'values.*' => 'required|string|max:255',
            'required' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'active' => 'boolean',
        ]);

        $data = $request->all();
        $data['values'] = array_filter($data['values']); // Remove empty values
        $data['sort_order'] = $data['sort_order'] ?? 0;

        ProductOption::create($data);

        return redirect()->route('admin.product-options.index')
                        ->with('success', 'Opção de produto criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductOption $productOption)
    {
        $productOption->load('products');
        return view('admin.product-options.show', compact('productOption'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductOption $productOption)
    {
        return view('admin.product-options.edit', compact('productOption'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductOption $productOption)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:select,color,text,number',
            'description' => 'nullable|string',
            'values' => 'required|array|min:1',
            'values.*' => 'required|string|max:255',
            'required' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'active' => 'boolean',
        ]);

        $data = $request->all();
        $data['values'] = array_filter($data['values']); // Remove empty values
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $productOption->update($data);

        return redirect()->route('admin.product-options.index')
                        ->with('success', 'Opção de produto atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductOption $productOption)
    {
        // Check if option is being used by products
        if ($productOption->products()->exists()) {
            return redirect()->route('admin.product-options.index')
                           ->with('error', 'Não é possível excluir uma opção que está sendo usada por produtos.');
        }

        $productOption->delete();

        return redirect()->route('admin.product-options.index')
                        ->with('success', 'Opção de produto excluída com sucesso!');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(ProductOption $productOption)
    {
        $productOption->update(['active' => !$productOption->active]);

        $status = $productOption->active ? 'ativada' : 'desativada';
        
        return redirect()->back()
                        ->with('success', "Opção {$status} com sucesso!");
    }
} 