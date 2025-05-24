<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'bank_name',
        'agency',
        'account_number',
        'initial_balance',
        'current_balance',
        'status',
        'description',
    ];

    protected $casts = [
        'initial_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
    ];

    // Relacionamentos
    public function receivables(): HasMany
    {
        return $this->hasMany(AccountReceivable::class);
    }

    public function payables(): HasMany
    {
        return $this->hasMany(AccountPayable::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(FinancialTransaction::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Accessors
    public function getFormattedCurrentBalanceAttribute()
    {
        return 'R$ ' . number_format($this->current_balance, 2, ',', '.');
    }
} 