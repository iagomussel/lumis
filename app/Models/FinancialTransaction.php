<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FinancialTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'type',
        'category',
        'amount',
        'description',
        'reference_date',
        'processed_at',
        'status',
        'payment_method',
        'reference_number',
        'transactionable_id',
        'transactionable_type',
        'user_id',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'reference_date' => 'date',
        'processed_at' => 'datetime',
    ];

    // Relacionamentos
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    public function scopeTransfer($query)
    {
        return $query->where('type', 'transfer');
    }

    public function scopeProcessed($query)
    {
        return $query->where('status', 'processed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        $formatted = 'R$ ' . number_format(abs($this->amount), 2, ',', '.');
        return $this->type === 'expense' ? "- {$formatted}" : "+ {$formatted}";
    }

    public function getSignedAmountAttribute()
    {
        return $this->type === 'expense' ? -abs($this->amount) : abs($this->amount);
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::created(function ($transaction) {
            if ($transaction->status === 'processed') {
                $account = $transaction->account;
                if ($transaction->type === 'income') {
                    $account->increment('current_balance', $transaction->amount);
                } elseif ($transaction->type === 'expense') {
                    $account->decrement('current_balance', $transaction->amount);
                }
            }
        });
    }
} 