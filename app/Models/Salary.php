<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gaji_pokok',
        'tunjangan_jabatan',
        'tunjangan_transport',
        'tunjangan_makan',
        'tunjangan_kehadiran',
        'tunjangan_kesehatan',
        'tunjangan_lainnya',
        'effective_date',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'gaji_pokok' => 'decimal:2',
        'tunjangan_jabatan' => 'decimal:2',
        'tunjangan_transport' => 'decimal:2',
        'tunjangan_makan' => 'decimal:2',
        'tunjangan_kehadiran' => 'decimal:2',
        'tunjangan_kesehatan' => 'decimal:2',
        'tunjangan_lainnya' => 'decimal:2',
        'effective_date' => 'date',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(SalaryHistory::class, 'user_id', 'user_id');
    }

    // Computed Properties
    public function getTotalTunjanganAttribute(): float
    {
        return (float) (
            $this->tunjangan_jabatan +
            $this->tunjangan_transport +
            $this->tunjangan_makan +
            $this->tunjangan_kehadiran +
            $this->tunjangan_kesehatan +
            $this->tunjangan_lainnya
        );
    }

    public function getTotalGajiAttribute(): float
    {
        return (float) ($this->gaji_pokok + $this->total_tunjangan);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeCurrent($query)
    {
        return $query->where('effective_date', '<=', now())
                     ->orderBy('effective_date', 'desc');
    }
}