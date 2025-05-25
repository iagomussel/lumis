<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'order_number',
        'customer_id',
        'user_id',
        'status',
        'subtotal',
        'discount',
        'tax',
        'shipping',
        'total',
        'total_amount', // For e-commerce compatibility
        'shipping_address',
        'shipping_number',
        'shipping_complement',
        'shipping_neighborhood',
        'shipping_city',
        'shipping_state',
        'shipping_zip_code',
        'payment_method',
        'payment_status',
        'notes',
        'shipped_at',
        'delivered_at',
        'stripe_payment_intent_id',
        'shipping_address_json',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // Relacionamentos
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    // Accessors
    public function getFormattedTotalAttribute()
    {
        return 'R$ ' . number_format($this->total, 2, ',', '.');
    }

    public function getFormattedSubtotalAttribute()
    {
        return 'R$ ' . number_format($this->subtotal, 2, ',', '.');
    }

    public function getFullShippingAddressAttribute()
    {
        $address = $this->shipping_address;
        if ($this->shipping_number) {
            $address .= ', ' . $this->shipping_number;
        }
        if ($this->shipping_complement) {
            $address .= ', ' . $this->shipping_complement;
        }
        if ($this->shipping_neighborhood) {
            $address .= ' - ' . $this->shipping_neighborhood;
        }
        if ($this->shipping_city && $this->shipping_state) {
            $address .= ' - ' . $this->shipping_city . '/' . $this->shipping_state;
        }
        if ($this->shipping_zip_code) {
            $address .= ' - CEP: ' . $this->shipping_zip_code;
        }
        return $address;
    }

    // Boot method para gerar número do pedido
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = 'PED-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
