<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'brand',
        'model',
        'serial_number',
        'purchase_date',
        'warranty_until',
        'status',
        'location',
        'max_width',
        'max_height',
        'max_temperature',
        'min_temperature',
        'max_pressure',
        'capabilities',
        'maintenance_schedule',
        'last_maintenance',
        'next_maintenance',
        'maintenance_notes',
        'usage_hours',
        'description',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_until' => 'date',
        'last_maintenance' => 'date',
        'next_maintenance' => 'date',
        'capabilities' => 'array',
    ];

    // Relacionamentos
    public function productionJobs(): HasMany
    {
        return $this->hasMany(ProductionJob::class);
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    public function scopeNeedsMaintenance($query)
    {
        return $query->where('next_maintenance', '<=', now()->addDays(7));
    }

    // Accessors
    public function getIsAvailableAttribute()
    {
        return $this->status === 'active';
    }

    public function getMaintenanceStatusAttribute()
    {
        if (!$this->next_maintenance) {
            return 'not_scheduled';
        }

        $daysUntilMaintenance = now()->diffInDays($this->next_maintenance, false);

        if ($daysUntilMaintenance < 0) {
            return 'overdue';
        } elseif ($daysUntilMaintenance <= 7) {
            return 'due_soon';
        } else {
            return 'ok';
        }
    }

    public function getFormattedDimensionsAttribute()
    {
        if ($this->max_width && $this->max_height) {
            return "{$this->max_width}cm x {$this->max_height}cm";
        }
        return '-';
    }
} 