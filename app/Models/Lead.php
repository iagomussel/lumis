<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'position',
        'status',
        'source',
        'score',
        'estimated_value',
        'probability',
        'expected_close_date',
        'user_id',
        'notes',
        'custom_fields',
        'last_contact_at',
        'next_follow_up_at',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'custom_fields' => 'array',
        'expected_close_date' => 'date',
        'last_contact_at' => 'datetime',
        'next_follow_up_at' => 'datetime',
    ];

    protected $attributes = [
        'score' => 0,
        'probability' => 0,
        'status' => 'new',
    ];

    // Relacionamentos
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeQualified($query)
    {
        return $query->where('status', 'qualified');
    }

    public function scopeWon($query)
    {
        return $query->where('status', 'won');
    }

    public function scopeLost($query)
    {
        return $query->where('status', 'lost');
    }

    public function scopeHighScore($query)
    {
        return $query->where('score', '>=', 70);
    }

    // Accessors
    public function getFormattedEstimatedValueAttribute()
    {
        return $this->estimated_value ? 'R$ ' . number_format($this->estimated_value, 2, ',', '.') : null;
    }

    public function getProbabilityPercentageAttribute()
    {
        return $this->probability . '%';
    }
}
