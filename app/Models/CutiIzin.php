<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CutiIzin extends Model
{
    use HasFactory;

    protected $table = 'cuti_izin';

    protected $fillable = [
        'user_id',
        'jenis',
        'tanggal_pengajuan',
        'tanggal_mulai',
        'tanggal_selesai',
        'jumlah_hari',
        'alasan',
        'file_pendukung',
        'status',
        'approved_by',
        'keterangan_approval',
        'catatan_admin',
        'tanggal_approval',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_approval' => 'datetime',
    ];

    /**
     * ✅ OTOMATIS ISI FIELD SAAT CREATE/UPDATE
     */
    protected static function boot()
    {
        parent::boot();

        // Event: Sebelum data disimpan (creating = insert baru)
        static::creating(function ($cutiIzin) {
            // Auto-fill tanggal_pengajuan jika kosong
            if (empty($cutiIzin->tanggal_pengajuan)) {
                $cutiIzin->tanggal_pengajuan = now();
            }

            // Auto-hitung jumlah_hari jika kosong
            if (empty($cutiIzin->jumlah_hari) && $cutiIzin->tanggal_mulai && $cutiIzin->tanggal_selesai) {
                $tanggalMulai = Carbon::parse($cutiIzin->tanggal_mulai);
                $tanggalSelesai = Carbon::parse($cutiIzin->tanggal_selesai);
                $cutiIzin->jumlah_hari = $tanggalMulai->diffInDays($tanggalSelesai) + 1;
            }
        });

        // Event: Sebelum data diupdate
        static::updating(function ($cutiIzin) {
            // Auto-hitung ulang jumlah_hari jika tanggal berubah
            if ($cutiIzin->isDirty(['tanggal_mulai', 'tanggal_selesai'])) {
                $tanggalMulai = Carbon::parse($cutiIzin->tanggal_mulai);
                $tanggalSelesai = Carbon::parse($cutiIzin->tanggal_selesai);
                $cutiIzin->jumlah_hari = $tanggalMulai->diffInDays($tanggalSelesai) + 1;
            }
        });
    }

    /**
     * Relasi ke User (pengaju)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke User (yang approve)
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Status Checker
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isDisetujui()
    {
        return $this->status === 'disetujui';
    }

    public function isDitolak()
    {
        return $this->status === 'ditolak';
    }

    /**
     * Scope untuk filter status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter jenis
     */
    public function scopeJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }

    /**
     * ✅ Accessor untuk fallback (jika data lama masih null)
     */
    public function getTanggalPengajuanAttribute($value)
    {
        // Jika null atau tahun 1970, gunakan created_at
        if (!$value || Carbon::parse($value)->year < 2020) {
            return $this->created_at;
        }
        return $value;
    }

    public function getJumlahHariAttribute($value)
    {
        // Jika null, hitung dari tanggal
        if (is_null($value) || $value == 0) {
            if ($this->tanggal_mulai && $this->tanggal_selesai) {
                return Carbon::parse($this->tanggal_mulai)->diffInDays(Carbon::parse($this->tanggal_selesai)) + 1;
            }
            return 0;
        }
        return $value;
    }
}