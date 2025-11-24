<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SalaryComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_komponen',
        'jenis',
        'tipe_perhitungan',
        'nilai',
        'is_active',
        'is_taxable',
        'deskripsi',
    ];

    protected $casts = [
        'nilai' => 'decimal:2',
        'is_active' => 'boolean',
        'is_taxable' => 'boolean',
    ];

    // Relationships
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_salary_components')
                    ->withPivot('custom_value', 'start_date', 'end_date', 'is_active')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeTunjangan($query)
    {
        return $query->where('jenis', 'tunjangan');
    }

    public function scopePotongan($query)
    {
        return $query->where('jenis', 'potongan');
    }

    public function scopeTaxable($query)
    {
        return $query->where('is_taxable', true);
    }

    // Helper Methods
    public function calculateValue(float $baseAmount): float
    {
        if ($this->tipe_perhitungan === 'persentase') {
            return ($baseAmount * $this->nilai) / 100;
        }
        return (float) $this->nilai;
    }

    public function isTunjangan(): bool
    {
        return $this->jenis === 'tunjangan';
    }

    public function isPotongan(): bool
    {
        return $this->jenis === 'potongan';
    }
}