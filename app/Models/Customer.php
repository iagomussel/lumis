<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Customer extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'mobile',
        'document',
        'document_type',
        'type',
        'company_name',
        'state_registration',
        'municipal_registration',
        'address',
        'address_number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'zip_code',
        'country',
        'status',
        'credit_limit',
        'current_balance',
        'notes',
        'birth_date',
        'gender',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'birth_date' => 'date',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relacionamentos
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeIndividual($query)
    {
        return $query->where('type', 'individual');
    }

    public function scopeCompany($query)
    {
        return $query->where('type', 'company');
    }

    public function scopeBlocked($query)
    {
        return $query->where('status', 'blocked');
    }

    // Accessors
    public function getFormattedCreditLimitAttribute()
    {
        return 'R$ ' . number_format($this->credit_limit, 2, ',', '.');
    }

    public function getFormattedCurrentBalanceAttribute()
    {
        return 'R$ ' . number_format($this->current_balance, 2, ',', '.');
    }

    public function getFullAddressAttribute()
    {
        $address = $this->address;
        if ($this->address_number) {
            $address .= ', ' . $this->address_number;
        }
        if ($this->complement) {
            $address .= ', ' . $this->complement;
        }
        if ($this->neighborhood) {
            $address .= ' - ' . $this->neighborhood;
        }
        if ($this->city && $this->state) {
            $address .= ' - ' . $this->city . '/' . $this->state;
        }
        if ($this->zip_code) {
            $address .= ' - CEP: ' . $this->zip_code;
        }
        return $address;
    }
}
