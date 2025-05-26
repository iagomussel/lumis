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
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
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
            'active_variants' => ProductVariant::where('is_active', true)->count(),
            'low_stock_variants' => ProductVariant::whereColumn('inventory_quantity', '<=', 'inventory_quantity_alert')->count(),
            'out_of_stock_variants' => ProductVariant::where('inventory_quantity', '<=', 0)->count(),
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
            'title' => 'nullable|string|max:255',
            'sku' => 'nullable|string|max:100|unique:product_variants,sku',
            'barcode' => 'nullable|string|max:100|unique:product_variants,barcode',
            'price' => 'nullable|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'inventory_quantity' => 'required|integer|min:0',
            'inventory_quantity_alert' => 'nullable|integer|min:0',
            'track_inventory' => 'boolean',
            'continue_selling_when_out_of_stock' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'requires_shipping' => 'boolean',
            'taxable' => 'boolean',
            'is_active' => 'boolean',
            'option_values' => 'required|array',
            'option_values.*' => 'required|exists:product_variant_option_values,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);

            // Generate SKU if not provided
            $sku = $request->sku;
            if (empty($sku)) {
                $sku = ProductVariant::generateUniqueSku($product->sku, $request->option_values);
            }

            // Generate title if not provided
            $title = $request->title;
            if (empty($title)) {
                $optionValues = ProductVariantOptionValue::whereIn('id', $request->option_values)->get();
                $title = $product->name . ' - ' . $optionValues->pluck('display_value')->join(' / ');
            }

            $variant = ProductVariant::create([
                'product_id' => $request->product_id,
                'title' => $title,
                'sku' => $sku,
                'barcode' => $request->barcode,
                'price' => $request->price,
                'compare_at_price' => $request->compare_at_price,
                'cost_price' => $request->cost_price,
                'inventory_quantity' => $request->inventory_quantity,
                'inventory_quantity_alert' => $request->inventory_quantity_alert ?? 5,
                'track_inventory' => $request->boolean('track_inventory', true),
                'continue_selling_when_out_of_stock' => $request->boolean('continue_selling_when_out_of_stock', false),
                'weight' => $request->weight,
                'length' => $request->length,
                'width' => $request->width,
                'height' => $request->height,
                'requires_shipping' => $request->boolean('requires_shipping', true),
                'taxable' => $request->boolean('taxable', true),
                'is_active' => $request->boolean('is_active', true),
                'metafields' => $request->metafields ?? [],
            ]);

            // Associate option values
            $variant->variantOptionValues()->sync($request->option_values);

            DB::commit();

            return redirect()->route('admin.product-variants.index')
                ->with('success', 'Variação de produto criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
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
            'title' => 'nullable|string|max:255',
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
            'price' => 'nullable|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'inventory_quantity' => 'required|integer|min:0',
            'inventory_quantity_alert' => 'nullable|integer|min:0',
            'track_inventory' => 'boolean',
            'continue_selling_when_out_of_stock' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'requires_shipping' => 'boolean',
            'taxable' => 'boolean',
            'is_active' => 'boolean',
            'option_values' => 'required|array',
            'option_values.*' => 'required|exists:product_variant_option_values,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $productVariant->update([
                'title' => $request->title,
                'sku' => $request->sku,
                'barcode' => $request->barcode,
                'price' => $request->price,
                'compare_at_price' => $request->compare_at_price,
                'cost_price' => $request->cost_price,
                'inventory_quantity' => $request->inventory_quantity,
                'inventory_quantity_alert' => $request->inventory_quantity_alert ?? 5,
                'track_inventory' => $request->boolean('track_inventory', true),
                'continue_selling_when_out_of_stock' => $request->boolean('continue_selling_when_out_of_stock', false),
                'weight' => $request->weight,
                'length' => $request->length,
                'width' => $request->width,
                'height' => $request->height,
                'requires_shipping' => $request->boolean('requires_shipping', true),
                'taxable' => $request->boolean('taxable', true),
                'is_active' => $request->boolean('is_active', true),
                'metafields' => $request->metafields ?? [],
            ]);

            // Update option values
            $productVariant->variantOptionValues()->sync($request->option_values);

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
            
            return redirect()->route('admin.product-variants.index')
                ->with('success', 'Variação de produto excluída com sucesso!');
        } catch (\Exception $e) {
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
            'is_active' => !$productVariant->is_active
        ]);

        $status = $productVariant->is_active ? 'ativada' : 'desativada';
        
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
            ->where('is_active', true)
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
                        'is_active' => true,
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
