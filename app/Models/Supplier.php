<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_name',
        'email',
        'phone',
        'mobile',
        'cnpj',
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
        'contact_person',
        'website',
        'notes',
        'rating',
        'payment_terms',
        'discount_percentage',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
    ];

    // Relacionamentos
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeBlocked($query)
    {
        return $query->where('status', 'blocked');
    }

    // Accessors
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
