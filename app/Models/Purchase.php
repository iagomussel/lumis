<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_number',
        'supplier_id',
        'user_id',
        'type',
        'status',
        'subtotal',
        'discount',
        'tax',
        'shipping',
        'total',
        'payment_terms',
        'payment_method',
        'delivery_date',
        'quote_valid_until',
        'notes',
        'terms_conditions',
        'sent_at',
        'confirmed_at',
        'received_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total' => 'decimal:2',
        'delivery_date' => 'date',
        'quote_valid_until' => 'date',
        'sent_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    // Relacionamentos
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeQuotation($query)
    {
        return $query->where('type', 'quotation');
    }

    public function scopePurchaseOrder($query)
    {
        return $query->where('type', 'purchase_order');
    }

    // Accessors
    public function getFormattedTotalAttribute()
    {
        return 'R$ ' . number_format($this->total, 2, ',', '.');
    }

    // Static methods
    public static function getPossibleStatuses()
    {
        return ['pending', 'ordered', 'received', 'cancelled'];
    }

    // Boot method para gerar número da compra
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($purchase) {
            if (!$purchase->purchase_number) {
                $prefix = $purchase->type === 'quotation' ? 'COT' : 'COM';
                $purchase->purchase_number = $prefix . '-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
