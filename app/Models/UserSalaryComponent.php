<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSalaryComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'salary_component_id',
        'custom_value',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'custom_value' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function salaryComponent(): BelongsTo
    {
        return $this->belongsTo(SalaryComponent::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('start_date', '<=', now())
                    ->where(function ($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', now());
                    });
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Helper Methods
    public function getValue(float $baseAmount = 0): float
    {
        $component = $this->salaryComponent;
        
        // Jika ada custom value, gunakan itu
        if ($this->custom_value !== null) {
            return (float) $this->custom_value;
        }
        
        // Jika tidak, gunakan nilai dari komponen
        return $component->calculateValue($baseAmount);
    }

    public function isExpired(): bool
    {
        return $this->end_date && $this->end_date->isPast();
    }

    public function isActive(): bool
    {
        $now = now();
        $active = $this->is_active 
                && $this->start_date <= $now 
                && ($this->end_date === null || $this->end_date >= $now);
        
        return $active;
    }
}