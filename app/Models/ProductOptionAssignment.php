<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductOptionAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_option_id',
        'available_values',
        'required',
        'sort_order',
    ];

    protected $casts = [
        'available_values' => 'array',
        'required' => 'boolean',
    ];

    // Relacionamentos
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productOption(): BelongsTo
    {
        return $this->belongsTo(ProductOption::class);
    }

    // Scopes
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function scopeRequired($query)
    {
        return $query->where('required', true);
    }

    // Accessors
    public function getEffectiveValuesAttribute()
    {
        return $this->available_values ?? $this->productOption->values;
    }

    public function getIsRequiredAttribute()
    {
        return $this->required ?? $this->productOption->required;
    }
} 