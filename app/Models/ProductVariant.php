<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'barcode',
        'title',
        'price',
        'compare_at_price',
        'cost_price',
        'inventory_quantity',
        'track_inventory',
        'continue_selling_when_out_of_stock',
        'inventory_policy',
        'fulfillment_service',
        'weight',
        'length',
        'width',
        'height',
        'requires_shipping',
        'taxable',
        'is_active',
        'position',
        'meta_title',
        'meta_description',
        'image_ids',
        'featured_image',
        'option_values',
        'metafields',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:3',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'track_inventory' => 'boolean',
        'continue_selling_when_out_of_stock' => 'boolean',
        'requires_shipping' => 'boolean',
        'taxable' => 'boolean',
        'is_active' => 'boolean',
        'image_ids' => 'array',
        'option_values' => 'array',
        'metafields' => 'array',
    ];

    // Relacionamentos
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->where('track_inventory', true)
                    ->where('inventory_quantity', '<=', 5); // Consider low stock as 5 or less
    }

    public function scopeInStock($query)
    {
        return $query->where(function($q) {
            $q->where('track_inventory', false)
              ->orWhere('inventory_quantity', '>', 0);
        });
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('track_inventory', true)
                    ->where('inventory_quantity', '<=', 0);
    }

    public function scopeByPosition($query)
    {
        return $query->orderBy('position');
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return 'R$ ' . number_format($this->price, 2, ',', '.');
    }

    public function getFormattedComparePriceAttribute()
    {
        if (!$this->compare_at_price) return null;
        return 'R$ ' . number_format($this->compare_at_price, 2, ',', '.');
    }

    public function getFormattedCostPriceAttribute()
    {
        if (!$this->cost_price) return null;
        return 'R$ ' . number_format($this->cost_price, 2, ',', '.');
    }

    public function getDiscountPercentageAttribute()
    {
        if (!$this->compare_at_price || $this->compare_at_price <= $this->price) {
            return 0;
        }
        
        return round((($this->compare_at_price - $this->price) / $this->compare_at_price) * 100);
    }

    public function getIsOnSaleAttribute()
    {
        return $this->compare_at_price && $this->compare_at_price > $this->price;
    }

    public function getStockStatusAttribute()
    {
        if (!$this->track_inventory) {
            return 'unlimited';
        }
        
        if ($this->inventory_quantity <= 0) {
            return 'out_of_stock';
        } elseif ($this->inventory_quantity <= 5) {
            return 'low_stock';
        }
        return 'in_stock';
    }

    public function getStockStatusLabelAttribute()
    {
        return match($this->stock_status) {
            'unlimited' => 'Estoque ilimitado',
            'in_stock' => 'Em estoque',
            'low_stock' => 'Estoque baixo',
            'out_of_stock' => 'Fora de estoque',
            default => 'Desconhecido'
        };
    }

    public function getStockStatusColorAttribute()
    {
        return match($this->stock_status) {
            'unlimited' => 'bg-blue-100 text-blue-800',
            'in_stock' => 'bg-green-100 text-green-800',
            'low_stock' => 'bg-yellow-100 text-yellow-800',
            'out_of_stock' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getOptionValuesDisplayAttribute()
    {
        if (!$this->option_values) {
            return '';
        }

        return collect($this->option_values)->map(function ($value, $key) {
            return ucfirst($key) . ': ' . $value;
        })->join(' / ');
    }

    public function getDisplayTitleAttribute()
    {
        return $this->title ?: $this->option_values_display ?: 'Variante padrão';
    }

    public function getCanBePurchasedAttribute()
    {
        if (!$this->is_active || !$this->product->is_active) {
            return false;
        }

        if (!$this->track_inventory) {
            return true;
        }

        return $this->inventory_quantity > 0 || $this->continue_selling_when_out_of_stock;
    }

    public function getProfitMarginAttribute()
    {
        if (!$this->cost_price || $this->cost_price <= 0) {
            return null;
        }

        return round((($this->price - $this->cost_price) / $this->price) * 100, 2);
    }

    public function getVolumeAttribute()
    {
        if (!$this->length || !$this->width || !$this->height) {
            return null;
        }

        return $this->length * $this->width * $this->height; // cm³
    }

    // Business methods
    public function canBeOrdered($quantity = 1)
    {
        if (!$this->can_be_purchased) {
            return false;
        }

        if (!$this->track_inventory) {
            return true;
        }

        if ($this->continue_selling_when_out_of_stock) {
            return true;
        }

        return $this->inventory_quantity >= $quantity;
    }

    public function updateStock($quantity, $operation = 'subtract')
    {
        if (!$this->track_inventory) {
            return $this;
        }

        if ($operation === 'subtract') {
            $this->inventory_quantity = max(0, $this->inventory_quantity - $quantity);
        } else {
            $this->inventory_quantity += $quantity;
        }
        
        $this->save();
        
        return $this;
    }

    public function generateTitle()
    {
        if (!$this->option_values) {
            return null;
        }

        return collect($this->option_values)->values()->join(' / ');
    }

    public function calculateFinalPrice($quantity = 1)
    {
        // Base price
        $finalPrice = $this->price * $quantity;
        
        // Apply any quantity-based discounts here if needed
        // This could be extended to include bulk pricing rules
        
        return $finalPrice;
    }

    public function calculateShippingWeight()
    {
        return $this->weight ?: $this->product->weight ?: 0;
    }

    // Static methods
    public static function generateUniqueSku($productSku, $optionValues = [])
    {
        if (empty($optionValues)) {
            $baseSku = $productSku . '-DEFAULT';
        } else {
            $suffix = collect($optionValues)->map(function ($value) {
                return strtoupper(Str::limit(preg_replace('/[^A-Za-z0-9]/', '', $value), 3, ''));
            })->join('-');
            
            $baseSku = $productSku . '-' . $suffix;
        }

        $counter = 1;
        $finalSku = $baseSku;

        while (static::where('sku', $finalSku)->exists()) {
            $finalSku = $baseSku . '-' . $counter;
            $counter++;
        }

        return $finalSku;
    }

    public static function createFromProduct(Product $product, array $optionValues = [], array $overrides = [])
    {
        $sku = static::generateUniqueSku($product->sku, $optionValues);
        
        $variantData = array_merge([
            'product_id' => $product->id,
            'sku' => $sku,
            'title' => !empty($optionValues) ? collect($optionValues)->values()->join(' / ') : null,
            'price' => $product->price,
            'cost_price' => $product->cost_price,
            'inventory_quantity' => $product->stock_quantity ?? 0,
            'weight' => $product->weight,
            'requires_shipping' => true,
            'taxable' => true,
            'is_active' => true,
            'option_values' => $optionValues,
        ], $overrides);

        return static::create($variantData);
    }
} 