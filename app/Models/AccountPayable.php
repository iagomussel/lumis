<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountPayable extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'purchase_id',
        'account_id',
        'invoice_number',
        'description',
        'amount',
        'paid_amount',
        'due_date',
        'payment_date',
        'status',
        'payment_method',
        'notes',
        'late_fee',
        'discount',
        'category',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'due_date' => 'date',
        'payment_date' => 'date',
    ];

    // Relacionamentos
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
                    ->where('due_date', '<', now());
    }

    // Accessors
    public function getRemainingAmountAttribute()
    {
        return $this->amount - $this->paid_amount + $this->late_fee - $this->discount;
    }

    public function getFormattedAmountAttribute()
    {
        return 'R$ ' . number_format($this->amount, 2, ',', '.');
    }

    public function getFormattedRemainingAmountAttribute()
    {
        return 'R$ ' . number_format($this->remaining_amount, 2, ',', '.');
    }

    public function getDaysOverdueAttribute()
    {
        if ($this->status === 'paid' || $this->due_date >= now()) {
            return 0;
        }
        return now()->diffInDays($this->due_date);
    }
} 