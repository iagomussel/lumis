<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantOption;
use App\Models\ProductVariantOptionValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProductVariant::with(['product', 'variantOptionValues.variantOption']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('active', false);
            }
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'in_stock':
                    $query->where('inventory_quantity', '>', 0);
                    break;
                case 'low_stock':
                    $query->whereColumn('inventory_quantity', '<=', 'inventory_quantity_alert');
                    break;
                case 'out_of_stock':
                    $query->where('inventory_quantity', '<=', 0);
                    break;
            }
        }

        $variants = $query->orderBy('created_at', 'desc')->paginate(20);
        $products = Product::select('id', 'name')->orderBy('name')->get();

        // Statistics
        $stats = [
            'total_variants' => ProductVariant::count(),
            'active_variants' => ProductVariant::where('active', true)->count(),
            'low_stock_variants' => ProductVariant::whereColumn('stock_quantity', '<=', 'min_stock')->count(),
            'out_of_stock_variants' => ProductVariant::where('stock_quantity', '<=', 0)->count(),
        ];

        return view('admin.product-variants.index', compact('variants', 'products', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $products = Product::select('id', 'name')->orderBy('name')->get();
        $selectedProduct = null;
        $variantOptions = collect();

        if ($request->filled('product_id')) {
            $selectedProduct = Product::with('variantOptions.values')->find($request->product_id);
            if ($selectedProduct) {
                $variantOptions = $selectedProduct->variantOptions;
            }
        }

        return view('admin.product-variants.create', compact('products', 'selectedProduct', 'variantOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:product_variants,sku',
            'price_adjustment' => 'nullable|numeric',
            'stock_quantity' => 'required|integer|min:0',
            'active' => 'boolean',
            'option_names' => 'nullable|array',
            'option_values' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);

            // Processar opções da variação
            $optionValues = [];
            if ($request->filled('option_names') && $request->filled('option_values')) {
                $names = $request->option_names;
                $values = $request->option_values;
                
                for ($i = 0; $i < count($names); $i++) {
                    if (!empty($names[$i]) && !empty($values[$i])) {
                        $optionValues[strtolower($names[$i])] = $values[$i];
                    }
                }
            }

            $variant = ProductVariant::create([
                'product_id' => $request->product_id,
                'name' => $request->name,
                'sku' => $request->sku,
                'option_values' => $optionValues,
                'price_adjustment' => $request->price_adjustment ?? 0,
                'stock_quantity' => $request->stock_quantity,
                'active' => $request->boolean('active', true),
            ]);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Variação criada com sucesso!',
                    'variant' => $variant
                ]);
            }

            return redirect()->route('admin.product-variants.index')
                ->with('success', 'Variação de produto criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao criar variação: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Erro ao criar variação: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductVariant $productVariant)
    {
        $productVariant->load(['product', 'variantOptionValues.variantOption']);
        
        return view('admin.product-variants.show', compact('productVariant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductVariant $productVariant)
    {
        $productVariant->load(['product.variantOptions.values', 'variantOptionValues']);
        $products = Product::select('id', 'name')->orderBy('name')->get();
        
        return view('admin.product-variants.edit', compact('productVariant', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductVariant $productVariant)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'sku' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('product_variants', 'sku')->ignore($productVariant->id)
            ],
            'barcode' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('product_variants', 'barcode')->ignore($productVariant->id)
            ],
            'price_adjustment' => 'nullable|numeric',
            'cost_adjustment' => 'nullable|numeric',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
            'weight_adjustment' => 'nullable|numeric',
            'active' => 'boolean',
            'option_values' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $optionValues = json_decode($request->option_values, true);
            if (!is_array($optionValues)) {
                throw new \Exception('Valores das opções devem ser um JSON válido.');
            }

            $productVariant->update([
                'name' => $request->name,
                'sku' => $request->sku,
                'barcode' => $request->barcode,
                'option_values' => $optionValues,
                'price_adjustment' => $request->price_adjustment ?? 0,
                'cost_adjustment' => $request->cost_adjustment ?? 0,
                'stock_quantity' => $request->stock_quantity,
                'min_stock' => $request->min_stock ?? 0,
                'weight_adjustment' => $request->weight_adjustment ?? 0,
                'active' => $request->boolean('active', true),
            ]);

            DB::commit();

            return redirect()->route('admin.product-variants.index')
                ->with('success', 'Variação de produto atualizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao atualizar variação: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductVariant $productVariant)
    {
        try {
            $productVariant->delete();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Variação excluída com sucesso!'
                ]);
            }
            
            return redirect()->route('admin.product-variants.index')
                ->with('success', 'Variação de produto excluída com sucesso!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao excluir variação: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Erro ao excluir variação: ' . $e->getMessage());
        }
    }

    /**
     * Toggle variant status
     */
    public function toggleStatus(ProductVariant $productVariant)
    {
        $productVariant->update([
            'active' => !$productVariant->active
        ]);

        $status = $productVariant->active ? 'ativada' : 'desativada';
        
        return redirect()->back()
            ->with('success', "Variação {$status} com sucesso!");
    }

    /**
     * Bulk update inventory
     */
    public function bulkUpdateInventory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'variants' => 'required|array',
            'variants.*.id' => 'required|exists:product_variants,id',
            'variants.*.inventory_quantity' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            foreach ($request->variants as $variantData) {
                ProductVariant::where('id', $variantData['id'])
                    ->update(['inventory_quantity' => $variantData['inventory_quantity']]);
            }

            DB::commit();

            return response()->json(['message' => 'Estoque atualizado com sucesso!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erro ao atualizar estoque: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get product variant options via AJAX
     */
    public function getProductVariantOptions(Request $request, Product $product)
    {
        $variantOptions = $product->variantOptions()
            ->with('values')
            ->where('active', true)
            ->orderBy('position')
            ->get();

        return response()->json($variantOptions);
    }

    /**
     * Generate variant combinations for a product
     */
    public function generateCombinations(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'option_combinations' => 'required|array',
            'default_price' => 'nullable|numeric|min:0',
            'default_inventory' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $combinations = $request->option_combinations;
            $defaultPrice = $request->default_price;
            $defaultInventory = $request->default_inventory;
            $createdVariants = [];

            foreach ($combinations as $combination) {
                // Check if variant already exists
                $existingVariant = $product->variants()
                    ->whereHas('variantOptionValues', function($query) use ($combination) {
                        $query->whereIn('product_variant_option_values.id', $combination);
                    }, '=', count($combination))
                    ->first();

                if (!$existingVariant) {
                    // Get option values for title generation
                    $optionValues = ProductVariantOptionValue::whereIn('id', $combination)->get();
                    $title = $product->name . ' - ' . $optionValues->pluck('display_value')->join(' / ');
                    $sku = ProductVariant::generateUniqueSku($product->sku, $combination);

                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'title' => $title,
                        'sku' => $sku,
                        'price' => $defaultPrice,
                        'inventory_quantity' => $defaultInventory,
                        'inventory_quantity_alert' => 5,
                        'track_inventory' => true,
                        'requires_shipping' => true,
                        'taxable' => true,
                        'active' => true,
                    ]);

                    $variant->variantOptionValues()->sync($combination);
                    $createdVariants[] = $variant;
                }
            }

            DB::commit();

            return response()->json([
                'message' => count($createdVariants) . ' variações criadas com sucesso!',
                'created_count' => count($createdVariants)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erro ao gerar variações: ' . $e->getMessage()], 500);
        }
    }
}
