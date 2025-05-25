<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Boot the trait
     */
    protected static function bootLogsActivity()
    {
        static::created(function (Model $model) {
            $model->logActivity('create');
        });

        static::updated(function (Model $model) {
            $model->logActivity('update');
        });

        static::deleted(function (Model $model) {
            $model->logActivity('delete');
        });
    }

    /**
     * Log an activity for this model
     */
    public function logActivity($action, $description = null, $options = [])
    {
        $modelName = class_basename($this);
        $defaultDescription = $this->getActivityDescription($action);
        $description = $description ?? $defaultDescription;

        $logData = array_merge([
            'model_type' => get_class($this),
            'model_id' => $this->getKey(),
            'model_name' => $this->getLogName(),
            'category' => $this->getActivityCategory(),
        ], $options);

        // Add old/new values for updates
        if ($action === 'update' && $this->isDirty()) {
            [$oldValues, $newValues] = ActivityLog::getModelChanges($this);
            $logData['old_values'] = $oldValues;
            $logData['new_values'] = $newValues;
        }

        return ActivityLog::log($action, $description, $logData);
    }

    /**
     * Get the name to be used in logs
     */
    public function getLogName()
    {
        // Try common name attributes
        $nameFields = ['name', 'title', 'description', 'subject'];
        
        foreach ($nameFields as $field) {
            if (isset($this->attributes[$field])) {
                return $this->attributes[$field];
            }
        }

        // Fallback to model name with ID
        return class_basename($this) . ' #' . $this->getKey();
    }

    /**
     * Get the category for this model's activities
     */
    public function getActivityCategory()
    {
        $modelName = strtolower(class_basename($this));
        
        // Map model names to categories
        $categoryMap = [
            'user' => 'auth',
            'product' => 'product',
            'category' => 'product',
            'productoption' => 'product',
            'productvariant' => 'product',
            'order' => 'order',
            'customer' => 'customer',
            'supplier' => 'supplier',
            'purchase' => 'purchase',
            'lead' => 'lead',
            'promotion' => 'marketing',
            'accountreceivable' => 'financial',
            'accountpayable' => 'financial',
            'financialtransaction' => 'financial',
        ];

        return $categoryMap[$modelName] ?? 'general';
    }

    /**
     * Get the default description for an activity
     */
    protected function getActivityDescription($action)
    {
        $modelName = class_basename($this);
        $logName = $this->getLogName();

        switch ($action) {
            case 'create':
                return "Criou {$modelName}: {$logName}";
            case 'update':
                return "Atualizou {$modelName}: {$logName}";
            case 'delete':
                return "Excluiu {$modelName}: {$logName}";
            default:
                return ucfirst($action) . " {$modelName}: {$logName}";
        }
    }

    /**
     * Log a custom activity for this model
     */
    public function logCustomActivity($action, $description, $options = [])
    {
        return $this->logActivity($action, $description, $options);
    }

    /**
     * Check if this model should log activities
     */
    public function shouldLogActivity()
    {
        return property_exists($this, 'logActivity') ? $this->logActivity : true;
    }
} 