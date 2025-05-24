<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Design extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'dimensions',
        'dpi',
        'color_profile',
        'status',
        'is_template',
        'is_public',
        'tags',
        'created_by',
        'approved_by',
        'approved_at',
        'version',
        'parent_design_id',
        'usage_count',
        'notes',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'is_template' => 'boolean',
        'is_public' => 'boolean',
        'tags' => 'array',
        'dimensions' => 'array',
    ];

    // Relacionamentos
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function parentDesign(): BelongsTo
    {
        return $this->belongsTo(Design::class, 'parent_design_id');
    }

    public function childDesigns(): HasMany
    {
        return $this->hasMany(Design::class, 'parent_design_id');
    }

    public function productionJobs(): HasMany
    {
        return $this->hasMany(ProductionJob::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeTemplates($query)
    {
        return $query->where('is_template', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    // Accessors
    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
} 