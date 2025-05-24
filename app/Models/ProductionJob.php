<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_number',
        'order_id',
        'order_item_id',
        'design_id',
        'product_id',
        'quantity',
        'status',
        'priority',
        'assigned_to',
        'equipment_id',
        'estimated_start',
        'estimated_duration',
        'actual_start',
        'actual_end',
        'temperature',
        'pressure',
        'time_seconds',
        'quality_check_status',
        'quality_check_notes',
        'quality_checked_by',
        'quality_checked_at',
        'production_notes',
        'reject_quantity',
        'reject_reason',
        'materials_used',
    ];

    protected $casts = [
        'estimated_start' => 'datetime',
        'actual_start' => 'datetime',
        'actual_end' => 'datetime',
        'quality_checked_at' => 'datetime',
        'materials_used' => 'array',
    ];

    // Relacionamentos
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function design(): BelongsTo
    {
        return $this->belongsTo(Design::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function qualityChecker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'quality_checked_by');
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function qualityChecks(): HasMany
    {
        return $this->hasMany(QualityCheck::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    // Accessors
    public function getActualDurationAttribute()
    {
        if ($this->actual_start && $this->actual_end) {
            return $this->actual_start->diffInMinutes($this->actual_end);
        }
        return null;
    }

    public function getFormattedActualDurationAttribute()
    {
        $duration = $this->actual_duration;
        if ($duration) {
            $hours = floor($duration / 60);
            $minutes = $duration % 60;
            return sprintf('%dh %02dm', $hours, $minutes);
        }
        return '-';
    }

    public function getSuccessRateAttribute()
    {
        $produced = $this->quantity - ($this->reject_quantity ?? 0);
        return $this->quantity > 0 ? round(($produced / $this->quantity) * 100, 1) : 0;
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($job) {
            if (!$job->job_number) {
                $job->job_number = 'PROD-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
} 