<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'values',
        'required',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'values' => 'array',
        'required' => 'boolean',
        'active' => 'boolean',
    ];

    // Relacionamentos
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_option_assignments')
                    ->withPivot('available_values', 'required', 'sort_order')
                    ->withTimestamps();
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(ProductOptionAssignment::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeRequired($query)
    {
        return $query->where('required', true);
    }

    // Accessors
    public function getTypeDisplayAttribute()
    {
        return match($this->type) {
            'select' => 'Seleção',
            'color' => 'Cor',
            'text' => 'Texto',
            'number' => 'Número',
            default => ucfirst($this->type)
        };
    }

    // Business methods
    public function getAvailableValuesForProduct($productId)
    {
        $assignment = $this->assignments()->where('product_id', $productId)->first();
        
        if ($assignment && $assignment->available_values) {
            return $assignment->available_values;
        }
        
        return $this->values;
    }

    public function isRequiredForProduct($productId)
    {
        $assignment = $this->assignments()->where('product_id', $productId)->first();
        
        return $assignment ? $assignment->required : $this->required;
    }
} 