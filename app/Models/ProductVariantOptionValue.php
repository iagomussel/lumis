<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ProductVariantOptionValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variant_option_id',
        'value',
        'display_value',
        'position',
        'color_hex',
        'image_url',
        'icon',
        'price_modifier',
        'price_modifier_type',
        'affects_inventory',
        'affects_shipping',
        'weight_modifier',
        'is_available',
        'is_default',
        'slug',
        'description',
        'metafields',
        'max_quantity',
        'available_from',
        'available_until',
    ];

    protected $casts = [
        'price_modifier' => 'decimal:2',
        'weight_modifier' => 'decimal:3',
        'affects_inventory' => 'boolean',
        'affects_shipping' => 'boolean',
        'is_available' => 'boolean',
        'is_default' => 'boolean',
        'metafields' => 'array',
        'available_from' => 'date',
        'available_until' => 'date',
    ];

    // Relacionamentos
    public function option(): BelongsTo
    {
        return $this->belongsTo(ProductVariantOption::class, 'product_variant_option_id');
    }

    public function product()
    {
        return $this->hasOneThrough(Product::class, ProductVariantOption::class, 'id', 'id', 'product_variant_option_id', 'product_id');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeByPosition($query)
    {
        return $query->orderBy('position');
    }

    public function scopeInDateRange($query, $date = null)
    {
        $date = $date ?: now()->toDateString();
        
        return $query->where(function($q) use ($date) {
            $q->whereNull('available_from')
              ->orWhere('available_from', '<=', $date);
        })->where(function($q) use ($date) {
            $q->whereNull('available_until')
              ->orWhere('available_until', '>=', $date);
        });
    }

    // Accessors
    public function getDisplayValueAttribute($value)
    {
        return $value ?: $this->value;
    }

    public function getSlugAttribute($value)
    {
        return $value ?: Str::slug($this->value);
    }

    public function getFormattedPriceModifierAttribute()
    {
        if ($this->price_modifier == 0) {
            return null;
        }

        $sign = $this->price_modifier > 0 ? '+' : '';
        
        if ($this->price_modifier_type === 'percentage') {
            return $sign . number_format($this->price_modifier, 1) . '%';
        }
        
        return $sign . 'R$ ' . number_format($this->price_modifier, 2, ',', '.');
    }

    public function getFormattedWeightModifierAttribute()
    {
        if ($this->weight_modifier == 0) {
            return null;
        }

        $sign = $this->weight_modifier > 0 ? '+' : '';
        return $sign . number_format($this->weight_modifier, 0) . 'g';
    }

    public function getIsColorOptionAttribute()
    {
        return $this->option->type === 'color' && !empty($this->color_hex);
    }

    public function getIsImageOptionAttribute()
    {
        return $this->option->type === 'image' && !empty($this->image_url);
    }

    public function getHasIconAttribute()
    {
        return !empty($this->icon);
    }

    public function getIsCurrentlyAvailableAttribute()
    {
        if (!$this->is_available) {
            return false;
        }

        $today = now()->toDateString();
        
        if ($this->available_from && $this->available_from > $today) {
            return false;
        }
        
        if ($this->available_until && $this->available_until < $today) {
            return false;
        }
        
        return true;
    }

    public function getAvailabilityStatusAttribute()
    {
        if (!$this->is_available) {
            return 'disabled';
        }
        
        if (!$this->is_currently_available) {
            return 'out_of_season';
        }
        
        if ($this->max_quantity && $this->max_quantity <= 0) {
            return 'out_of_stock';
        }
        
        return 'available';
    }

    public function getAvailabilityStatusLabelAttribute()
    {
        return match($this->availability_status) {
            'available' => 'Disponível',
            'disabled' => 'Desabilitado',
            'out_of_season' => 'Fora de temporada',
            'out_of_stock' => 'Fora de estoque',
            default => 'Desconhecido'
        };
    }

    public function getAvailabilityStatusColorAttribute()
    {
        return match($this->availability_status) {
            'available' => 'bg-green-100 text-green-800',
            'disabled' => 'bg-gray-100 text-gray-800',
            'out_of_season' => 'bg-yellow-100 text-yellow-800',
            'out_of_stock' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Business methods
    public function calculatePriceModifier($basePrice)
    {
        if ($this->price_modifier == 0) {
            return 0;
        }

        if ($this->price_modifier_type === 'percentage') {
            return ($basePrice * $this->price_modifier) / 100;
        }
        
        return $this->price_modifier;
    }

    public function calculateFinalPrice($basePrice)
    {
        return $basePrice + $this->calculatePriceModifier($basePrice);
    }

    public function calculateWeightModifier($baseWeight)
    {
        return $baseWeight + $this->weight_modifier;
    }

    public function canBeSelected($quantity = 1)
    {
        if (!$this->is_currently_available) {
            return false;
        }
        
        if ($this->max_quantity && $quantity > $this->max_quantity) {
            return false;
        }
        
        return true;
    }

    public function reduceQuantity($quantity)
    {
        if ($this->max_quantity) {
            $this->max_quantity = max(0, $this->max_quantity - $quantity);
            $this->save();
        }
    }

    public function increaseQuantity($quantity)
    {
        if ($this->max_quantity !== null) {
            $this->max_quantity += $quantity;
            $this->save();
        }
    }

    // Static methods
    public static function createForOption(ProductVariantOption $option, $value, $attributes = [])
    {
        $position = static::where('product_variant_option_id', $option->id)->max('position') + 1;
        
        $valueData = array_merge([
            'product_variant_option_id' => $option->id,
            'value' => $value,
            'position' => $position,
        ], $attributes);

        return static::create($valueData);
    }

    public static function getPriceModifierTypes()
    {
        return [
            'fixed' => 'Valor fixo',
            'percentage' => 'Porcentagem',
        ];
    }
}
