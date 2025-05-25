<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Carbon\Carbon;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'action',
        'model_type',
        'model_id',
        'model_name',
        'description',
        'old_values',
        'new_values',
        'metadata',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'category',
        'severity',
        'session_id',
        'performed_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'metadata' => 'array',
        'performed_at' => 'datetime',
    ];

    protected $dates = [
        'performed_at',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function model(): MorphTo
    {
        return $this->morphTo('model', 'model_type', 'model_id');
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    public function scopeByModel($query, $modelType, $modelId = null)
    {
        $query = $query->where('model_type', $modelType);
        
        if ($modelId) {
            $query->where('model_id', $modelId);
        }
        
        return $query;
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('performed_at', [$startDate, $endDate]);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('performed_at', '>=', Carbon::now()->subDays($days));
    }

    // Accessors
    public function getSeverityColorAttribute()
    {
        return [
            'info' => 'blue',
            'warning' => 'yellow',
            'error' => 'red',
            'critical' => 'red',
        ][$this->severity] ?? 'gray';
    }

    public function getSeverityIconAttribute()
    {
        return [
            'info' => 'ti-info-circle',
            'warning' => 'ti-alert-triangle',
            'error' => 'ti-x-circle',
            'critical' => 'ti-alert-octagon',
        ][$this->severity] ?? 'ti-circle';
    }

    public function getActionIconAttribute()
    {
        return [
            'create' => 'ti-plus',
            'update' => 'ti-edit',
            'delete' => 'ti-trash',
            'login' => 'ti-login',
            'logout' => 'ti-logout',
            'view' => 'ti-eye',
            'download' => 'ti-download',
            'upload' => 'ti-upload',
            'approve' => 'ti-check',
            'reject' => 'ti-x',
            'convert' => 'ti-refresh',
        ][$this->action] ?? 'ti-activity';
    }

    public function getFormattedPerformedAtAttribute()
    {
        return $this->performed_at->format('d/m/Y H:i:s');
    }

    // Static methods for creating logs
    public static function log($action, $description, $options = [])
    {
        $user = Auth::user();
        
        return static::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'user_email' => $user?->email,
            'action' => $action,
            'model_type' => $options['model_type'] ?? null,
            'model_id' => $options['model_id'] ?? null,
            'model_name' => $options['model_name'] ?? null,
            'description' => $description,
            'old_values' => $options['old_values'] ?? null,
            'new_values' => $options['new_values'] ?? null,
            'metadata' => $options['metadata'] ?? null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'category' => $options['category'] ?? 'general',
            'severity' => $options['severity'] ?? 'info',
            'session_id' => session()->getId(),
            'performed_at' => now(),
        ]);
    }

    public static function logModel($action, $model, $description = null, $options = [])
    {
        $modelName = class_basename($model);
        $description = $description ?? ucfirst($action) . ' ' . $modelName;

        return static::log($action, $description, array_merge($options, [
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'model_name' => method_exists($model, 'getLogName') ? $model->getLogName() : $modelName,
        ]));
    }

    public static function logAuth($action, $description, $options = [])
    {
        return static::log($action, $description, array_merge($options, [
            'category' => 'auth',
        ]));
    }

    public static function logCritical($action, $description, $options = [])
    {
        return static::log($action, $description, array_merge($options, [
            'severity' => 'critical',
        ]));
    }

    public static function logError($action, $description, $options = [])
    {
        return static::log($action, $description, array_merge($options, [
            'severity' => 'error',
        ]));
    }

    public static function logWarning($action, $description, $options = [])
    {
        return static::log($action, $description, array_merge($options, [
            'severity' => 'warning',
        ]));
    }

    // Helper method to get changes for updates
    public static function getModelChanges($model)
    {
        if (!$model->isDirty()) {
            return [null, null];
        }

        $oldValues = [];
        $newValues = [];

        foreach ($model->getDirty() as $key => $newValue) {
            $oldValues[$key] = $model->getOriginal($key);
            $newValues[$key] = $newValue;
        }

        return [$oldValues, $newValues];
    }
}
