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
        'parent_id',
        'image',
    ];

    protected $casts = [
        'active' => 'boolean',
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
}
