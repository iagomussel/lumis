<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'quantity',
        'unit_price',
        'total_price',
        'custom_fields',
        'notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'custom_fields' => 'array',
    ];

    // Relacionamentos
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors
    public function getFormattedUnitPriceAttribute()
    {
        return 'R$ ' . number_format($this->unit_price, 2, ',', '.');
    }

    public function getFormattedTotalPriceAttribute()
    {
        return 'R$ ' . number_format($this->total_price, 2, ',', '.');
    }

    // Boot method para calcular total automaticamente
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->total_price = $item->quantity * $item->unit_price;
        });
    }
}
