<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'slug',
        'active',
        'show_in_ecommerce',
        'internal_notes',
        'parent_id',
        'image',
    ];

    protected $casts = [
        'active' => 'boolean',
        'show_in_ecommerce' => 'boolean',
    ];

    // Relacionamentos
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeEcommerce($query)
    {
        return $query->where('show_in_ecommerce', true);
    }

    public function scopeInternal($query)
    {
        return $query->where('show_in_ecommerce', false);
    }

    public function scopeInsumos($query)
    {
        return $query->where('type', 'insumo');
    }

    public function scopeAtivos($query)
    {
        return $query->where('type', 'ativo');
    }

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    // Accessors
    public function getTypeDisplayAttribute()
    {
        return match($this->type) {
            'insumo' => 'Insumo',
            'ativo' => 'Ativo',
            default => ucfirst($this->type)
        };
    }

    public function getUsageDisplayAttribute()
    {
        return $this->show_in_ecommerce ? 'E-commerce' : 'Controle Interno';
    }

    // Business methods
    public function getTotalCost()
    {
        return $this->products()
                    ->sum(\DB::raw('cost_price * stock_quantity'));
    }

    public function getTotalValue()
    {
        return $this->products()
                    ->sum(\DB::raw('price * stock_quantity'));
    }

    public function getROI()
    {
        $totalCost = $this->getTotalCost();
        $totalValue = $this->getTotalValue();
        
        if ($totalCost == 0) return 0;
        
        return round((($totalValue - $totalCost) / $totalCost) * 100, 2);
    }
}
