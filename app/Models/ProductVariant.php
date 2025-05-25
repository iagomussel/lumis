<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'name',
        'option_values',
        'price_adjustment',
        'cost_adjustment',
        'stock_quantity',
        'min_stock',
        'weight_adjustment',
        'active',
        'barcode',
        'images',
    ];

    protected $casts = [
        'option_values' => 'array',
        'price_adjustment' => 'decimal:2',
        'cost_adjustment' => 'decimal:2',
        'weight_adjustment' => 'decimal:3',
        'active' => 'boolean',
        'images' => 'array',
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
        return $query->where('active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'min_stock');
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock_quantity', '<=', 0);
    }

    // Accessors
    public function getFinalPriceAttribute()
    {
        return $this->product->price + $this->price_adjustment;
    }

    public function getFinalCostPriceAttribute()
    {
        return $this->product->cost_price + $this->cost_adjustment;
    }

    public function getFinalWeightAttribute()
    {
        return $this->product->weight + $this->weight_adjustment;
    }

    public function getFormattedFinalPriceAttribute()
    {
        return 'R$ ' . number_format($this->final_price, 2, ',', '.');
    }

    public function getFormattedFinalCostPriceAttribute()
    {
        return 'R$ ' . number_format($this->final_cost_price, 2, ',', '.');
    }

    public function getIsLowStockAttribute()
    {
        return $this->stock_quantity <= $this->min_stock;
    }

    public function getOptionValuesDisplayAttribute()
    {
        if (!$this->option_values) {
            return '';
        }

        return collect($this->option_values)->map(function ($value, $key) {
            return ucfirst($key) . ': ' . $value;
        })->join(', ');
    }

    public function getMainImageAttribute()
    {
        if ($this->images && count($this->images) > 0) {
            return $this->images[0];
        }

        return $this->product->main_image;
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock_quantity <= 0) {
            return 'out_of_stock';
        } elseif ($this->stock_quantity <= $this->min_stock) {
            return 'low_stock';
        }
        return 'in_stock';
    }

    // Business methods
    public function canBeOrdered($quantity = 1)
    {
        return $this->active && 
               $this->product->status === 'active' && 
               $this->stock_quantity >= $quantity;
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

    public function generateVariantName()
    {
        if (!$this->option_values) {
            return $this->product->name;
        }

        $optionParts = collect($this->option_values)->values()->join(' ');
        return $this->product->name . ' - ' . $optionParts;
    }

    // Generate unique SKU for variant
    public static function generateUniqueSku($productSku, $optionValues)
    {
        $suffix = collect($optionValues)->map(function ($value) {
            return strtoupper(substr($value, 0, 2));
        })->join('-');

        $baseSku = $productSku . '-' . $suffix;
        $counter = 1;
        $finalSku = $baseSku;

        while (static::where('sku', $finalSku)->exists()) {
            $finalSku = $baseSku . '-' . $counter;
            $counter++;
        }

        return $finalSku;
    }
} 