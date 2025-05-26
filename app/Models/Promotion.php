<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'code',
        'type',
        'discount_value',
        'discount_percentage',
        'minimum_order_value',
        'max_uses',
        'max_uses_per_customer',
        'current_uses',
        'starts_at',
        'ends_at',
        'status',
        'applicable_products',
        'applicable_categories',
        'applicable_customers',
        'send_email',
        'email_subject',
        'email_template',
        'email_sent_at'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'email_sent_at' => 'datetime',
        'applicable_products' => 'array',
        'applicable_categories' => 'array',
        'applicable_customers' => 'array',
        'send_email' => 'boolean',
        'discount_value' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'minimum_order_value' => 'decimal:2'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('starts_at', '<=', now())
                    ->where('ends_at', '>=', now());
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    public function scopeApplicableToProduct($query, $productId)
    {
        return $query->where(function($q) use ($productId) {
            $q->whereNull('applicable_products')
              ->orWhereJsonContains('applicable_products', $productId);
        });
    }

    public function scopeApplicableToCategory($query, $categoryId)
    {
        return $query->where(function($q) use ($categoryId) {
            $q->whereNull('applicable_categories')
              ->orWhereJsonContains('applicable_categories', $categoryId);
        });
    }

    public function scopeApplicableToCustomer($query, $customerId)
    {
        return $query->where(function($q) use ($customerId) {
            $q->whereNull('applicable_customers')
              ->orWhereJsonContains('applicable_customers', $customerId);
        });
    }

    // Relationships
    public function products()
    {
        if (!$this->applicable_products) {
            return collect();
        }
        
        return Product::whereIn('id', $this->applicable_products)->get();
    }

    public function categories()
    {
        if (!$this->applicable_categories) {
            return collect();
        }
        
        return Category::whereIn('id', $this->applicable_categories)->get();
    }

    public function customers()
    {
        if (!$this->applicable_customers) {
            return collect();
        }
        
        return Customer::whereIn('id', $this->applicable_customers)->get();
    }

    // Business Logic Methods
    public function isActive()
    {
        return $this->status === 'active' 
            && $this->starts_at <= now() 
            && $this->ends_at >= now();
    }

    public function isExpired()
    {
        return $this->ends_at < now();
    }

    public function canBeUsed()
    {
        if (!$this->isActive()) {
            return false;
        }

        if ($this->max_uses && $this->current_uses >= $this->max_uses) {
            return false;
        }

        return true;
    }

    public function canBeUsedByCustomer($customerId, $currentUses = 0)
    {
        if (!$this->canBeUsed()) {
            return false;
        }

        if ($this->applicable_customers && !in_array($customerId, $this->applicable_customers)) {
            return false;
        }

        if ($currentUses >= $this->max_uses_per_customer) {
            return false;
        }

        return true;
    }

    public function calculateDiscount($orderValue, $productIds = [], $categoryIds = [])
    {
        if (!$this->canBeUsed()) {
            return 0;
        }

        if ($this->minimum_order_value && $orderValue < $this->minimum_order_value) {
            return 0;
        }

        // Check product applicability
        if ($this->applicable_products && !empty($productIds)) {
            $hasApplicableProduct = !empty(array_intersect($this->applicable_products, $productIds));
            if (!$hasApplicableProduct) {
                return 0;
            }
        }

        // Check category applicability
        if ($this->applicable_categories && !empty($categoryIds)) {
            $hasApplicableCategory = !empty(array_intersect($this->applicable_categories, $categoryIds));
            if (!$hasApplicableCategory) {
                return 0;
            }
        }

        switch ($this->type) {
            case 'percentage_discount':
                return ($orderValue * $this->discount_percentage) / 100;
            
            case 'fixed_discount':
                return min($this->discount_value, $orderValue);
            
            case 'free_shipping':
                return 0; // Handled separately in shipping calculation
            
            default:
                return 0;
        }
    }

    public function incrementUsage()
    {
        $this->increment('current_uses');
    }

    public function getUsagePercentage()
    {
        if (!$this->max_uses) {
            return 0;
        }

        return ($this->current_uses / $this->max_uses) * 100;
    }

    public function getDaysRemaining()
    {
        if ($this->isExpired()) {
            return 0;
        }

        return now()->diffInDays($this->ends_at);
    }

    public function getStatusBadgeClass()
    {
        switch ($this->status) {
            case 'active':
                return $this->isExpired() ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800';
            case 'inactive':
                return 'bg-gray-100 text-gray-800';
            case 'expired':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    public function getStatusText()
    {
        if ($this->isExpired()) {
            return 'Expirada';
        }

        switch ($this->status) {
            case 'active':
                return 'Ativa';
            case 'inactive':
                return 'Inativa';
            case 'expired':
                return 'Expirada';
            default:
                return 'Desconhecido';
        }
    }

    public function getTypeText()
    {
        switch ($this->type) {
            case 'percentage_discount':
                return 'Desconto Percentual';
            case 'fixed_discount':
                return 'Desconto Fixo';
            case 'buy_one_get_one':
                return 'Leve 2 Pague 1';
            case 'free_shipping':
                return 'Frete Grátis';
            case 'bundle_deal':
                return 'Pacote Promocional';
            default:
                return 'Desconhecido';
        }
    }

    // Auto-update status based on dates
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($promotion) {
            if ($promotion->ends_at < now()) {
                $promotion->status = 'expired';
            }
        });
    }
}
