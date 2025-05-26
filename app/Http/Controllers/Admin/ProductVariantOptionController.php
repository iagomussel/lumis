<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariantOption;
use App\Models\ProductVariantOptionValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductVariantOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProductVariantOption::with(['product', 'values']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('display_name', 'like', "%{$search}%")
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $options = $query->orderBy('position')->paginate(20);
        $products = Product::select('id', 'name')->orderBy('name')->get();

        // Statistics
        $stats = [
            'total_options' => ProductVariantOption::count(),
            'active_options' => ProductVariantOption::where('is_active', true)->count(),
            'required_options' => ProductVariantOption::where('is_required', true)->count(),
            'total_values' => ProductVariantOptionValue::count(),
        ];

        return view('admin.product-variant-options.index', compact('options', 'products', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $products = Product::select('id', 'name')->orderBy('name')->get();
        $selectedProduct = null;

        if ($request->filled('product_id')) {
            $selectedProduct = Product::find($request->product_id);
        }

        return view('admin.product-variant-options.create', compact('products', 'selectedProduct'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'display_name' => 'nullable|string|max:255',
            'type' => 'required|in:select,color,text,number,image',
            'position' => 'nullable|integer|min:0',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'validation_rules' => 'nullable|array',
            'display_style' => 'nullable|in:dropdown,radio,checkbox,swatch,button',
            'values' => 'required|array|min:1',
            'values.*.value' => 'required|string|max:255',
            'values.*.display_value' => 'nullable|string|max:255',
            'values.*.color_hex' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'values.*.image_url' => 'nullable|url',
            'values.*.price_modifier' => 'nullable|numeric',
            'values.*.price_modifier_type' => 'nullable|in:fixed,percentage',
            'values.*.weight_modifier' => 'nullable|numeric',
            'values.*.is_default' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Get next position if not provided
            $position = $request->position;
            if (is_null($position)) {
                $position = ProductVariantOption::where('product_id', $request->product_id)->max('position') + 1;
            }

            $option = ProductVariantOption::create([
                'product_id' => $request->product_id,
                'name' => $request->name,
                'display_name' => $request->display_name ?: $request->name,
                'type' => $request->type,
                'position' => $position,
                'is_required' => $request->boolean('is_required', false),
                'is_active' => $request->boolean('is_active', true),
                'description' => $request->description,
                'validation_rules' => $request->validation_rules ?? [],
                'display_style' => $request->display_style ?: 'dropdown',
            ]);

            // Create option values
            foreach ($request->values as $index => $valueData) {
                ProductVariantOptionValue::create([
                    'product_variant_option_id' => $option->id,
                    'value' => $valueData['value'],
                    'display_value' => $valueData['display_value'] ?: $valueData['value'],
                    'position' => $index,
                    'color_hex' => $valueData['color_hex'] ?? null,
                    'image_url' => $valueData['image_url'] ?? null,
                    'price_modifier' => $valueData['price_modifier'] ?? 0,
                    'price_modifier_type' => $valueData['price_modifier_type'] ?? 'fixed',
                    'weight_modifier' => $valueData['weight_modifier'] ?? 0,
                    'is_default' => $valueData['is_default'] ?? false,
                    'is_available' => true,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.product-variant-options.index')
                ->with('success', 'Opção de variação criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao criar opção: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductVariantOption $productVariantOption)
    {
        $productVariantOption->load(['product', 'values' => function($query) {
            $query->orderBy('position');
        }]);
        
        return view('admin.product-variant-options.show', compact('productVariantOption'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductVariantOption $productVariantOption)
    {
        $productVariantOption->load(['product', 'values' => function($query) {
            $query->orderBy('position');
        }]);
        $products = Product::select('id', 'name')->orderBy('name')->get();
        
        return view('admin.product-variant-options.edit', compact('productVariantOption', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductVariantOption $productVariantOption)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'display_name' => 'nullable|string|max:255',
            'type' => 'required|in:select,color,text,number,image',
            'position' => 'nullable|integer|min:0',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
            'validation_rules' => 'nullable|array',
            'display_style' => 'nullable|in:dropdown,radio,checkbox,swatch,button',
            'values' => 'required|array|min:1',
            'values.*.id' => 'nullable|exists:product_variant_option_values,id',
            'values.*.value' => 'required|string|max:255',
            'values.*.display_value' => 'nullable|string|max:255',
            'values.*.color_hex' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'values.*.image_url' => 'nullable|url',
            'values.*.price_modifier' => 'nullable|numeric',
            'values.*.price_modifier_type' => 'nullable|in:fixed,percentage',
            'values.*.weight_modifier' => 'nullable|numeric',
            'values.*.is_default' => 'nullable|boolean',
            'values.*.is_available' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $productVariantOption->update([
                'name' => $request->name,
                'display_name' => $request->display_name ?: $request->name,
                'type' => $request->type,
                'position' => $request->position ?? $productVariantOption->position,
                'is_required' => $request->boolean('is_required', false),
                'is_active' => $request->boolean('is_active', true),
                'description' => $request->description,
                'validation_rules' => $request->validation_rules ?? [],
                'display_style' => $request->display_style ?: 'dropdown',
            ]);

            // Get existing value IDs
            $existingValueIds = $productVariantOption->values->pluck('id')->toArray();
            $submittedValueIds = [];

            // Update or create values
            foreach ($request->values as $index => $valueData) {
                if (isset($valueData['id']) && in_array($valueData['id'], $existingValueIds)) {
                    // Update existing value
                    $value = ProductVariantOptionValue::find($valueData['id']);
                    $value->update([
                        'value' => $valueData['value'],
                        'display_value' => $valueData['display_value'] ?: $valueData['value'],
                        'position' => $index,
                        'color_hex' => $valueData['color_hex'] ?? null,
                        'image_url' => $valueData['image_url'] ?? null,
                        'price_modifier' => $valueData['price_modifier'] ?? 0,
                        'price_modifier_type' => $valueData['price_modifier_type'] ?? 'fixed',
                        'weight_modifier' => $valueData['weight_modifier'] ?? 0,
                        'is_default' => $valueData['is_default'] ?? false,
                        'is_available' => $valueData['is_available'] ?? true,
                    ]);
                    $submittedValueIds[] = $valueData['id'];
                } else {
                    // Create new value
                    $newValue = ProductVariantOptionValue::create([
                        'product_variant_option_id' => $productVariantOption->id,
                        'value' => $valueData['value'],
                        'display_value' => $valueData['display_value'] ?: $valueData['value'],
                        'position' => $index,
                        'color_hex' => $valueData['color_hex'] ?? null,
                        'image_url' => $valueData['image_url'] ?? null,
                        'price_modifier' => $valueData['price_modifier'] ?? 0,
                        'price_modifier_type' => $valueData['price_modifier_type'] ?? 'fixed',
                        'weight_modifier' => $valueData['weight_modifier'] ?? 0,
                        'is_default' => $valueData['is_default'] ?? false,
                        'is_available' => true,
                    ]);
                    $submittedValueIds[] = $newValue->id;
                }
            }

            // Delete values that were not submitted
            $valuesToDelete = array_diff($existingValueIds, $submittedValueIds);
            if (!empty($valuesToDelete)) {
                ProductVariantOptionValue::whereIn('id', $valuesToDelete)->delete();
            }

            DB::commit();

            return redirect()->route('admin.product-variant-options.index')
                ->with('success', 'Opção de variação atualizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erro ao atualizar opção: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductVariantOption $productVariantOption)
    {
        try {
            $productVariantOption->delete();
            
            return redirect()->route('admin.product-variant-options.index')
                ->with('success', 'Opção de variação excluída com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao excluir opção: ' . $e->getMessage());
        }
    }

    /**
     * Toggle option status
     */
    public function toggleStatus(ProductVariantOption $productVariantOption)
    {
        $productVariantOption->update([
            'is_active' => !$productVariantOption->is_active
        ]);

        $status = $productVariantOption->is_active ? 'ativada' : 'desativada';
        
        return redirect()->back()
            ->with('success', "Opção {$status} com sucesso!");
    }

    /**
     * Reorder options
     */
    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'options' => 'required|array',
            'options.*.id' => 'required|exists:product_variant_options,id',
            'options.*.position' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            foreach ($request->options as $optionData) {
                ProductVariantOption::where('id', $optionData['id'])
                    ->update(['position' => $optionData['position']]);
            }

            DB::commit();

            return response()->json(['message' => 'Ordem atualizada com sucesso!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erro ao reordenar: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get option values via AJAX
     */
    public function getValues(ProductVariantOption $productVariantOption)
    {
        $values = $productVariantOption->values()
            ->where('is_available', true)
            ->orderBy('position')
            ->get();

        return response()->json($values);
    }

    /**
     * Add value to option
     */
    public function addValue(Request $request, ProductVariantOption $productVariantOption)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required|string|max:255',
            'display_value' => 'nullable|string|max:255',
            'color_hex' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'image_url' => 'nullable|url',
            'price_modifier' => 'nullable|numeric',
            'price_modifier_type' => 'nullable|in:fixed,percentage',
            'weight_modifier' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $position = $productVariantOption->values()->max('position') + 1;

            $value = ProductVariantOptionValue::create([
                'product_variant_option_id' => $productVariantOption->id,
                'value' => $request->value,
                'display_value' => $request->display_value ?: $request->value,
                'position' => $position,
                'color_hex' => $request->color_hex,
                'image_url' => $request->image_url,
                'price_modifier' => $request->price_modifier ?? 0,
                'price_modifier_type' => $request->price_modifier_type ?? 'fixed',
                'weight_modifier' => $request->weight_modifier ?? 0,
                'is_available' => true,
            ]);

            return response()->json([
                'message' => 'Valor adicionado com sucesso!',
                'value' => $value
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao adicionar valor: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove value from option
     */
    public function removeValue(ProductVariantOption $productVariantOption, ProductVariantOptionValue $value)
    {
        try {
            if ($value->product_variant_option_id !== $productVariantOption->id) {
                return response()->json(['error' => 'Valor não pertence a esta opção'], 400);
            }

            $value->delete();

            return response()->json(['message' => 'Valor removido com sucesso!']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao remover valor: ' . $e->getMessage()], 500);
        }
    }
}
