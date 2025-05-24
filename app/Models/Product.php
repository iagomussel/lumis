<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

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

    // Accessors
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
}
