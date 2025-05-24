<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'product_name',
        'product_sku',
        'quantity_ordered',
        'quantity_received',
        'unit_price',
        'total_price',
        'notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Relacionamentos
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
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

    public function getQuantityPendingAttribute()
    {
        return $this->quantity_ordered - $this->quantity_received;
    }

    // Boot method para calcular total automaticamente
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->total_price = $item->quantity_ordered * $item->unit_price;
        });
    }
}
