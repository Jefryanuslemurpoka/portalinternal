<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

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
        'tanggal_bayar',
        'gaji_pokok',
        'total_tunjangan',
        'total_potongan',
        'gaji_kotor',
        'gaji_bersih',
        'hari_kerja',
        'hari_hadir',
        'hari_izin',
        'hari_sakit',
        'hari_cuti',
        'hari_alpha',
        'jam_lembur',
        'upah_lembur',
        'jumlah_terlambat',
        'menit_terlambat',
        'detail_tunjangan',
        'detail_potongan',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'payment_date',
        'payment_method',
        'payment_notes',
        'generated_at',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'bulan' => 'integer',
        'gaji_pokok' => 'decimal:2',
        'total_tunjangan' => 'decimal:2',
        'total_potongan' => 'decimal:2',
        'gaji_kotor' => 'decimal:2',
        'gaji_bersih' => 'decimal:2',
        'jam_lembur' => 'decimal:2',
        'upah_lembur' => 'decimal:2',
        'detail_tunjangan' => 'array',
        'detail_potongan' => 'array',
    ];

    protected $dates = [
        'approved_at',
        'generated_at',
        'created_at',
        'updated_at',
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ==========================================
    // ACCESSOR - FORMAT TANGGAL
    // ==========================================
    
    public function getPeriodeStartFormattedAttribute(): string
    {
        return $this->periode_start ? Carbon::parse($this->periode_start)->format('d M Y') : '-';
    }

    public function getPeriodeEndFormattedAttribute(): string
    {
        return $this->periode_end ? Carbon::parse($this->periode_end)->format('d M Y') : '-';
    }

    public function getPeriodeStartShortAttribute(): string
    {
        return $this->periode_start ? Carbon::parse($this->periode_start)->format('d/m') : '-';
    }

    public function getPeriodeEndShortAttribute(): string
    {
        return $this->periode_end ? Carbon::parse($this->periode_end)->format('d/m') : '-';
    }

    public function getTanggalBayarFormattedAttribute(): string
    {
        return $this->tanggal_bayar ? Carbon::parse($this->tanggal_bayar)->format('d M Y') : '-';
    }

    public function getPaymentDateFormattedAttribute(): string
    {
        return $this->payment_date ? Carbon::parse($this->payment_date)->format('d M Y') : '-';
    }

    public function getApprovedAtFormattedAttribute(): string
    {
        return $this->approved_at ? Carbon::parse($this->approved_at)->format('d M Y H:i') : '-';
    }

    public function getGeneratedAtFormattedAttribute(): string
    {
        return $this->generated_at ? Carbon::parse($this->generated_at)->format('d M Y H:i') : '-';
    }

    // ==========================================
    // ACCESSOR - DATA HELPER
    // ==========================================

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

    // ==========================================
    // SCOPES
    // ==========================================

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

    // ==========================================
    // STATUS CHECK METHODS
    // ==========================================

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