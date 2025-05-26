<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class DeliverySchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_id',
        'tracking_code',
        'scheduled_date',
        'scheduled_time_start',
        'scheduled_time_end',
        'delivery_address',
        'delivery_city',
        'delivery_state',
        'delivery_zip_code',
        'delivery_notes',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'status',
        'confirmed_at',
        'dispatched_at',
        'delivered_at',
        'delivery_proof',
        'driver_name',
        'driver_phone',
        'vehicle_info',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'scheduled_time_start' => 'datetime:H:i',
        'scheduled_time_end' => 'datetime:H:i',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'dispatched_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    // Scopes
    public function scopeScheduledForToday($query)
    {
        return $query->where('scheduled_date', Carbon::today());
    }

    public function scopeScheduledForDate($query, $date)
    {
        return $query->where('scheduled_date', $date);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['scheduled', 'confirmed']);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_transit');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'delivered');
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'scheduled' => 'Agendado',
            'confirmed' => 'Confirmado',
            'in_transit' => 'Em Trânsito',
            'delivered' => 'Entregue',
            'cancelled' => 'Cancelado',
            default => 'Desconhecido'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'scheduled' => 'bg-blue-100 text-blue-800',
            'confirmed' => 'bg-green-100 text-green-800',
            'in_transit' => 'bg-yellow-100 text-yellow-800',
            'delivered' => 'bg-emerald-100 text-emerald-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getIsOverdueAttribute()
    {
        return $this->scheduled_date < Carbon::today() && !in_array($this->status, ['delivered', 'cancelled']);
    }

    public function getHasRemainingAmountAttribute()
    {
        return $this->remaining_amount > 0;
    }

    public function getFormattedScheduledTimeAttribute()
    {
        if ($this->scheduled_time_start && $this->scheduled_time_end) {
            return $this->scheduled_time_start->format('H:i') . ' - ' . $this->scheduled_time_end->format('H:i');
        }
        return 'Horário não definido';
    }

    // Business Methods
    public function generateTrackingCode()
    {
        do {
            $code = 'DEL' . strtoupper(substr(md5(uniqid()), 0, 8));
        } while (self::where('tracking_code', $code)->exists());
        
        $this->tracking_code = $code;
        return $code;
    }

    public function confirm()
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }

    public function dispatch($driverName = null, $driverPhone = null, $vehicleInfo = null)
    {
        $this->update([
            'status' => 'in_transit',
            'dispatched_at' => now(),
            'driver_name' => $driverName,
            'driver_phone' => $driverPhone,
            'vehicle_info' => $vehicleInfo,
        ]);
    }

    public function markAsDelivered($deliveryProof = null)
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
            'delivery_proof' => $deliveryProof,
        ]);
    }

    public function cancel()
    {
        $this->update([
            'status' => 'cancelled',
        ]);
    }

    public function updatePayment($paidAmount)
    {
        $this->update([
            'paid_amount' => $paidAmount,
            'remaining_amount' => $this->total_amount - $paidAmount,
        ]);
    }

    // Static methods
    public static function getStatusOptions()
    {
        return [
            'scheduled' => 'Agendado',
            'confirmed' => 'Confirmado',
            'in_transit' => 'Em Trânsito',
            'delivered' => 'Entregue',
            'cancelled' => 'Cancelado',
        ];
    }

    public static function getTodayStats()
    {
        $today = Carbon::today();
        
        return [
            'total' => self::scheduledForDate($today)->count(),
            'pending' => self::scheduledForDate($today)->pending()->count(),
            'in_progress' => self::scheduledForDate($today)->inProgress()->count(),
            'completed' => self::scheduledForDate($today)->completed()->count(),
        ];
    }
} 