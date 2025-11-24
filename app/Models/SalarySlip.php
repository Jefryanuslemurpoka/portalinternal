<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class SalarySlip extends Model
{
    use HasFactory;

    protected $fillable = [
        'slip_number',
        'user_id',
        'tahun',
        'bulan',
        'periode_start',
        'periode_end',
        'gaji_pokok',
        'total_tunjangan',
        'total_potongan',
        'gaji_kotor',
        'gaji_bersih',
        'hari_kerja',
        'hari_hadir',
        'hari_izin',
        'hari_sakit',
        'hari_alpha',
        'jam_lembur',
        'upah_lembur',
        'tunjangan_detail',
        'potongan_detail',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'payment_date',
        'payment_method',
        'payment_notes',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'bulan' => 'integer',
        'periode_start' => 'date',
        'periode_end' => 'date',
        'gaji_pokok' => 'decimal:2',
        'total_tunjangan' => 'decimal:2',
        'total_potongan' => 'decimal:2',
        'gaji_kotor' => 'decimal:2',
        'gaji_bersih' => 'decimal:2',
        'jam_lembur' => 'decimal:2',
        'upah_lembur' => 'decimal:2',
        'tunjangan_detail' => 'array',
        'potongan_detail' => 'array',
        'approved_at' => 'datetime',
        'payment_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slip_number)) {
                $model->slip_number = self::generateSlipNumber($model->tahun, $model->bulan);
            }
        });
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Helper Methods
    public static function generateSlipNumber($tahun, $bulan): string
    {
        $prefix = 'SLIP';
        $date = sprintf('%04d%02d', $tahun, $bulan);
        $random = strtoupper(Str::random(6));
        return "{$prefix}/{$date}/{$random}";
    }

    public function getBulanNameAttribute(): string
    {
        $bulanIndonesia = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $bulanIndonesia[$this->bulan] ?? '';
    }

    public function getPeriodeAttribute(): string
    {
        return "{$this->bulan_name} {$this->tahun}";
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'draft' => 'bg-gray-100 text-gray-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'paid' => 'bg-blue-100 text-blue-800',
        ];
        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'draft' => 'Draft',
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'paid' => 'Sudah Dibayar',
        ];
        return $labels[$this->status] ?? 'Unknown';
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForPeriode($query, $tahun, $bulan)
    {
        return $query->where('tahun', $tahun)->where('bulan', $bulan);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', ['draft', 'pending', 'approved']);
    }

    // Status Methods
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function canEdit(): bool
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    public function canApprove(): bool
    {
        return $this->status === 'pending';
    }

    public function canPay(): bool
    {
        return $this->status === 'approved';
    }
}