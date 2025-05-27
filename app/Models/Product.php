<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'description',
        'detailed_description',
        'short_description',
        'sku',
        'barcode',
        'price',
        'promotional_price',
        'promotion_start',
        'promotion_end',
        'cost_price',
        'stock_quantity',
        'min_stock',
        'max_stock',
        'min_stock_alert',
        'status',
        'online_sale',
        'is_customizable',
        'custom_fields',
        'images',
        'files',
        'weight',
        'length',
        'width',
        'height',
        'free_shipping',
        'shipping_notes',
        'dimensions',
        'brand',
        'model',
        'featured',
        'specifications',
        'rating',
        'reviews_count',
        'category_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'promotional_price' => 'decimal:2',
        'promotion_start' => 'datetime',
        'promotion_end' => 'datetime',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:3',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'free_shipping' => 'boolean',
        'rating' => 'decimal:2',
        'is_customizable' => 'boolean',
        'featured' => 'boolean',
        'online_sale' => 'boolean',
        'custom_fields' => 'array',
        'images' => 'array',
        'files' => 'array',
        'specifications' => 'array',
    ];

    // Relacionamentos
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function productionJobs(): HasMany
    {
        return $this->hasMany(ProductionJob::class);
    }

    public function designs(): BelongsToMany
    {
        return $this->belongsToMany(Design::class, 'product_designs')
                    ->withPivot('is_default', 'design_notes')
                    ->withTimestamps();
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function activeVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->where('active', true);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeOnlineSale($query)
    {
        return $query->where('online_sale', true);
    }

    public function scopeAvailableOnline($query)
    {
        return $query->where('status', 'active')
                     ->where('online_sale', true)
                     ->where('stock_quantity', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'min_stock');
    }

    public function scopeCustomizable($query)
    {
        return $query->where('is_customizable', true);
    }

    public function scopeOnPromotion($query)
    {
        return $query->whereNotNull('promotional_price')
                     ->where('promotion_start', '<=', now())
                     ->where('promotion_end', '>=', now());
    }

    public function scopeWithShippingInfo($query)
    {
        return $query->whereNotNull('weight')
                     ->whereNotNull('length')
                     ->whereNotNull('width')
                     ->whereNotNull('height');
    }

    public function scopeFreeShipping($query)
    {
        return $query->where('free_shipping', true);
    }

    public function scopeByWeightRange($query, $minWeight, $maxWeight)
    {
        return $query->whereBetween('weight', [$minWeight, $maxWeight]);
    }

    public function scopeRequiresProduction($query)
    {
        return $query->where('is_customizable', true);
    }

    public function scopeInProduction($query)
    {
        return $query->whereHas('productionJobs', function($q) {
            $q->whereIn('status', ['pending', 'in_progress', 'quality_check']);
        });
    }

    // Accessors & Mutators
    public function getFormattedPriceAttribute()
    {
        return 'R$ ' . number_format($this->price, 2, ',', '.');
    }

    public function getFormattedPromotionalPriceAttribute()
    {
        return $this->promotional_price ? 'R$ ' . number_format($this->promotional_price, 2, ',', '.') : null;
    }

    public function getCurrentPriceAttribute()
    {
        return $this->getIsOnPromotionAttribute() ? $this->promotional_price : $this->price;
    }

    public function getFormattedCurrentPriceAttribute()
    {
        return 'R$ ' . number_format($this->getCurrentPriceAttribute(), 2, ',', '.');
    }

    public function getFormattedCostPriceAttribute()
    {
        return 'R$ ' . number_format($this->cost_price, 2, ',', '.');
    }

    public function getProfitMarginAttribute()
    {
        if (!$this->cost_price || $this->cost_price == 0) {
            return 0;
        }
        
        return round((($this->current_price - $this->cost_price) / $this->cost_price) * 100, 2);
    }

    public function getFormattedProfitMarginAttribute()
    {
        return $this->profit_margin . '%';
    }

    public function getIsLowStockAttribute()
    {
        return $this->stock_quantity <= $this->min_stock;
    }

    public function getIsOnPromotionAttribute()
    {
        return $this->promotional_price && 
               $this->promotion_start && 
               $this->promotion_end &&
               now()->between($this->promotion_start, $this->promotion_end);
    }

    public function getDiscountPercentageAttribute()
    {
        if (!$this->getIsOnPromotionAttribute()) {
            return 0;
        }
        
        return round((($this->price - $this->promotional_price) / $this->price) * 100);
    }

    public function getMainImageAttribute()
    {
        return $this->images && count($this->images) > 0 ? $this->images[0] : null;
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock_quantity <= 0) {
            return 'out_of_stock';
        } elseif ($this->stock_quantity <= $this->min_stock_alert) {
            return 'low_stock';
        }
        return 'in_stock';
    }

    public function getFormattedRatingAttribute()
    {
        return number_format($this->rating, 1);
    }

    // Shipping-related accessors
    public function getHasShippingInfoAttribute()
    {
        return $this->weight && $this->length && $this->width && $this->height;
    }

    public function getShippingWeightAttribute()
    {
        return $this->weight ?? config('shipping.package.default_weight', 0.5);
    }

    public function getShippingLengthAttribute()
    {
        return $this->length ?? config('shipping.package.default_length', 20);
    }

    public function getShippingWidthAttribute()
    {
        return $this->width ?? config('shipping.package.default_width', 15);
    }

    public function getShippingHeightAttribute()
    {
        return $this->height ?? config('shipping.package.default_height', 5);
    }

    public function getVolumeAttribute()
    {
        return $this->shipping_length * $this->shipping_width * $this->shipping_height;
    }

    public function getFormattedVolumeAttribute()
    {
        return number_format($this->volume / 1000, 2) . ' L';
    }

    public function getFormattedDimensionsAttribute()
    {
        return "{$this->shipping_length} x {$this->shipping_width} x {$this->shipping_height} cm";
    }

    public function getFormattedWeightAttribute()
    {
        return number_format($this->shipping_weight, 3) . ' kg';
    }

    // Production-related accessors
    public function getIsInProductionAttribute()
    {
        return $this->productionJobs()
                    ->whereIn('status', ['pending', 'in_progress', 'quality_check'])
                    ->exists();
    }

    public function getProductionQueueCountAttribute()
    {
        return $this->productionJobs()
                    ->where('status', 'pending')
                    ->count();
    }

    public function getPendingProductionQuantityAttribute()
    {
        return $this->productionJobs()
                    ->whereIn('status', ['pending', 'in_progress'])
                    ->sum('quantity');
    }

    public function getDefaultDesignAttribute()
    {
        return $this->designs()
                    ->wherePivot('is_default', true)
                    ->first();
    }

    public function getAvailableDesignsCountAttribute()
    {
        return $this->designs()->count();
    }

    // Variant-related accessors
    public function getHasVariantsAttribute()
    {
        return $this->variants()->exists();
    }

    public function getActiveVariantsCountAttribute()
    {
        return $this->activeVariants()->count();
    }

    public function getTotalVariantStockAttribute()
    {
        return $this->activeVariants()->sum('stock_quantity');
    }

    // Business logic methods
    public function canBeOrdered($quantity = 1)
    {
        return $this->status === 'active' && 
               $this->stock_quantity >= $quantity;
    }

    public function canBeSoldOnline($quantity = 1)
    {
        return $this->online_sale && 
               $this->canBeOrdered($quantity);
    }

    public function isOnPromotion()
    {
        return $this->promotional_price && 
               $this->promotion_start && 
               $this->promotion_end &&
               now()->between($this->promotion_start, $this->promotion_end);
    }

    public function needsProduction()
    {
        return $this->is_customizable || 
               $this->stock_quantity <= $this->min_stock;
    }

    public function calculateShippingFor($quantity, $cep = null)
    {
        $totalWeight = $this->shipping_weight * $quantity;
        $packageDimensions = $this->calculatePackageDimensions($quantity);
        
        if ($cep && app()->bound('App\Services\ShippingService')) {
            $shippingService = app('App\Services\ShippingService');
            return $shippingService->calculateShipping(
                $cep, 
                $totalWeight,
                $packageDimensions['length'],
                $packageDimensions['height'],
                $packageDimensions['width']
            );
        }
        
        return null;
    }

    private function calculatePackageDimensions($quantity)
    {
        // Simple packaging calculation - can be enhanced
        $itemsPerLayer = ceil(sqrt($quantity));
        $layers = ceil($quantity / $itemsPerLayer);
        
        return [
            'length' => max($this->shipping_length, $this->shipping_length * min($itemsPerLayer, 2)),
            'width' => max($this->shipping_width, $this->shipping_width * min($itemsPerLayer, 2)),
            'height' => max($this->shipping_height, $this->shipping_height * $layers)
        ];
    }

    public function updateStock($quantity, $operation = 'subtract')
    {
        if ($operation === 'subtract') {
            $this->stock_quantity = max(0, $this->stock_quantity - $quantity);
        } else {
            $this->stock_quantity += $quantity;
        }
        
        $this->save();
        
        return $this;
    }

    public function createProductionJob($quantity, $orderId = null, $priority = 'normal')
    {
        if (!$this->is_customizable) {
            return null;
        }

        return $this->productionJobs()->create([
            'order_id' => $orderId,
            'quantity' => $quantity,
            'priority' => $priority,
            'status' => 'pending',
            'parameters' => $this->getDefaultProductionParameters(),
            'estimated_completion' => now()->addDays($this->getEstimatedProductionDays()),
        ]);
    }

    private function getDefaultProductionParameters()
    {
        // Return default parameters since category doesn't have production fields yet
        return [
            'temperature' => 180,
            'pressure' => 2.5,
            'time' => 60,
            'material' => 'polyester',
        ];
    }

    private function getEstimatedProductionDays()
    {
        // Base estimate on category and complexity
        $baseDays = 1;
        
        if ($this->is_customizable) {
            $baseDays += 1;
        }
        
        // Default complexity estimate since category doesn't have this field yet
        $baseDays += 1;
        
        return $baseDays;
    }

    // Variant management methods
    public function createVariant($optionValues, $additionalData = [])
    {
        $sku = ProductVariant::generateUniqueSku($this->sku, $optionValues);
        $name = $this->generateVariantName($optionValues);

        $variantData = array_merge([
            'sku' => $sku,
            'name' => $name,
            'option_values' => $optionValues,
            'stock_quantity' => 0,
            'min_stock' => $this->min_stock,
            'active' => true,
        ], $additionalData);

        return $this->variants()->create($variantData);
    }

    private function generateVariantName($optionValues)
    {
        $optionParts = collect($optionValues)->values()->join(' ');
        return $this->name . ' - ' . $optionParts;
    }

    public function getVariantByOptions($optionValues)
    {
        return $this->variants()
                    ->where('option_values', json_encode($optionValues))
                    ->where('active', true)
                    ->first();
    }

    public function hasOptionCombination($optionValues)
    {
        return $this->getVariantByOptions($optionValues) !== null;
    }

    public function getAvailableStockForOptions($optionValues)
    {
        $variant = $this->getVariantByOptions($optionValues);
        
        if ($variant) {
            return $variant->stock_quantity;
        }
        
        // Se não tem variantes, usar estoque do produto principal
        return $this->has_variants ? 0 : $this->stock_quantity;
    }

    public function canBeOrderedWithOptions($optionValues, $quantity = 1)
    {
        if (!$this->has_variants) {
            return $this->canBeOrdered($quantity);
        }

        $variant = $this->getVariantByOptions($optionValues);
        
        return $variant ? $variant->canBeOrdered($quantity) : false;
    }
}
