<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariantOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'display_name',
        'position',
        'type',
        'is_required',
        'is_visible',
        'display_style',
        'help_text',
        'validation_rules',
        'min_selections',
        'max_selections',
        'metafields',
        'is_active',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_visible' => 'boolean',
        'is_active' => 'boolean',
        'validation_rules' => 'array',
        'metafields' => 'array',
    ];

    // Relacionamentos
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(ProductVariantOptionValue::class)->orderBy('position');
    }

    public function activeValues(): HasMany
    {
        return $this->hasMany(ProductVariantOptionValue::class)->where('is_available', true)->orderBy('position');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    public function scopeByPosition($query)
    {
        return $query->orderBy('position');
    }

    // Accessors
    public function getDisplayNameAttribute($value)
    {
        return $value ?: $this->name;
    }

    public function getTypeDisplayAttribute()
    {
        return match($this->type) {
            'select' => 'Seleção',
            'color' => 'Cor',
            'image' => 'Imagem',
            'text' => 'Texto',
            default => 'Desconhecido'
        };
    }

    public function getDisplayStyleDisplayAttribute()
    {
        return match($this->display_style) {
            'dropdown' => 'Lista suspensa',
            'buttons' => 'Botões',
            'swatches' => 'Amostras',
            'list' => 'Lista',
            default => 'Padrão'
        };
    }

    public function getHasValuesAttribute()
    {
        return $this->values()->count() > 0;
    }

    public function getAvailableValuesCountAttribute()
    {
        return $this->activeValues()->count();
    }

    // Business methods
    public function addValue($value, $attributes = [])
    {
        $position = $this->values()->max('position') + 1;
        
        $valueData = array_merge([
            'value' => $value,
            'position' => $position,
        ], $attributes);

        return $this->values()->create($valueData);
    }

    public function reorderValues(array $valueIds)
    {
        foreach ($valueIds as $position => $valueId) {
            $this->values()->where('id', $valueId)->update(['position' => $position]);
        }
    }

    public function getDefaultValue()
    {
        return $this->values()->where('is_default', true)->first();
    }

    public function setDefaultValue($valueId)
    {
        // Remove default from all values
        $this->values()->update(['is_default' => false]);
        
        // Set new default
        $this->values()->where('id', $valueId)->update(['is_default' => true]);
    }

    public function validateSelection($selectedValues)
    {
        $selectedCount = is_array($selectedValues) ? count($selectedValues) : 1;
        
        if ($this->is_required && $selectedCount < $this->min_selections) {
            return false;
        }
        
        if ($selectedCount > $this->max_selections) {
            return false;
        }
        
        return true;
    }

    public function canHaveMultipleSelections()
    {
        return $this->max_selections > 1;
    }

    // Static methods
    public static function createForProduct(Product $product, $name, $attributes = [])
    {
        $position = static::where('product_id', $product->id)->max('position') + 1;
        
        $optionData = array_merge([
            'product_id' => $product->id,
            'name' => $name,
            'position' => $position,
        ], $attributes);

        return static::create($optionData);
    }

    public static function getAvailableTypes()
    {
        return [
            'select' => 'Seleção',
            'color' => 'Cor',
            'image' => 'Imagem',
            'text' => 'Texto',
        ];
    }

    public static function getAvailableDisplayStyles()
    {
        return [
            'dropdown' => 'Lista suspensa',
            'buttons' => 'Botões',
            'swatches' => 'Amostras',
            'list' => 'Lista',
        ];
    }
}
