<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'uuid',
        'name',
        'nik',
        'gelar',
        'email',
        'password',
        'phone',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'nomor_rekening',
        'role',
        'divisi',
        'jabatan',
        'foto',
        'foto_ktp',
        'foto_npwp',
        'foto_bpjs',
        'dokumen_kontrak',
        'status',
        'aktif_dari',
        'aktif_sampai',
        'sisa_cuti',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'tanggal_lahir' => 'date',
            'aktif_dari' => 'date',
            'aktif_sampai' => 'date',
        ];
    }

    // Auto generate UUID saat create
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    // Route key menggunakan UUID
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    // Role Checker Methods
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isKaryawan()
    {
        return $this->role === 'karyawan';
    }

    // ========================================
    // RELASI EXISTING (Jangan diubah)
    // ========================================
    
    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function cutiIzin()
    {
        return $this->hasMany(CutiIzin::class);
    }

    public function pengumuman()
    {
        return $this->hasMany(Pengumuman::class, 'created_by');
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class);
    }

    public function approvedCutiIzin()
    {
        return $this->hasMany(CutiIzin::class, 'approved_by');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    public function unreadNotificationsCount()
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    public function unreadNotifications()
    {
        return $this->notifications()->where('is_read', false);
    }

    // ========================================
    // RELASI UNTUK SISTEM GAJI (BARU)
    // ========================================

    /**
     * Get the active salary for the user
     * Gaji yang sedang aktif saat ini
     */
    public function salary()
    {
        return $this->hasOne(Salary::class)
                    ->where('status', 'aktif')
                    ->orderBy('effective_date', 'desc');
    }

    /**
     * Get all salaries (including inactive) for the user
     * Semua history gaji termasuk yang non-aktif
     */
    public function salaries()
    {
        return $this->hasMany(Salary::class)
                    ->orderBy('effective_date', 'desc');
    }

    /**
     * Get salary slips for the user
     * Semua slip gaji user
     */
    public function salarySlips()
    {
        return $this->hasMany(SalarySlip::class)
                    ->orderBy('tahun', 'desc')
                    ->orderBy('bulan', 'desc');
    }

    /**
     * Get assigned salary components (tunjangan & potongan)
     * Komponen gaji yang di-assign khusus untuk user ini
     */
    public function salaryComponents()
    {
        return $this->belongsToMany(SalaryComponent::class, 'user_salary_components')
                    ->withPivot('custom_value', 'start_date', 'end_date', 'is_active')
                    ->withTimestamps();
    }

    /**
     * Get active salary components only
     * Hanya komponen yang sedang aktif
     */
    public function activeSalaryComponents()
    {
        return $this->belongsToMany(SalaryComponent::class, 'user_salary_components')
                    ->wherePivot('is_active', true)
                    ->wherePivot('start_date', '<=', now())
                    ->where(function ($query) {
                        $query->whereNull('user_salary_components.end_date')
                              ->orWhere('user_salary_components.end_date', '>=', now());
                    })
                    ->withPivot('custom_value', 'start_date', 'end_date', 'is_active')
                    ->withTimestamps();
    }

    /**
     * Get salary histories for the user
     * History perubahan gaji
     */
    public function salaryHistories()
    {
        return $this->hasMany(SalaryHistory::class)
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Get approved salary slips (as approver)
     * Slip gaji yang user ini approve
     */
    public function approvedSlips()
    {
        return $this->hasMany(SalarySlip::class, 'approved_by');
    }

    // ========================================
    // SCOPES UNTUK SISTEM GAJI
    // ========================================

    /**
     * Scope: Only active employees
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope: Has salary setup
     * Filter user yang sudah punya master gaji
     */
    public function scopeHasSalary($query)
    {
        return $query->whereHas('salary');
    }

    // ========================================
    // HELPER METHODS UNTUK SISTEM GAJI
    // ========================================

    /**
     * Check if user has active salary
     */
    public function hasActiveSalary(): bool
    {
        return $this->salary()->exists();
    }

    /**
     * Get current month salary slip
     * Slip gaji bulan ini
     */
    public function getCurrentMonthSlip()
    {
        return $this->salarySlips()
                    ->where('tahun', now()->year)
                    ->where('bulan', now()->month)
                    ->first();
    }

    /**
     * Get total salary for specific month
     * Total gaji yang sudah dibayar di bulan tertentu
     */
    public function getTotalSalary($tahun, $bulan)
    {
        $slip = $this->salarySlips()
                     ->where('tahun', $tahun)
                     ->where('bulan', $bulan)
                     ->where('status', 'paid')
                     ->first();

        return $slip ? $slip->gaji_bersih : 0;
    }

    /**
     * Get unpaid slips count
     * Jumlah slip yang belum dibayar
     */
    public function getUnpaidSlipsCount(): int
    {
        return $this->salarySlips()
                    ->whereIn('status', ['approved'])
                    ->count();
    }

    /**
     * Get total gaji tahun ini
     */
    public function getTotalGajiTahunIniAttribute()
    {
        return $this->salarySlips()
                    ->where('tahun', now()->year)
                    ->where('status', 'paid')
                    ->sum('gaji_bersih');
    }

    /**
     * Get latest paid slip
     * Slip gaji terakhir yang sudah dibayar
     */
    public function getLatestPaidSlip()
    {
        return $this->salarySlips()
                    ->where('status', 'paid')
                    ->orderBy('tahun', 'desc')
                    ->orderBy('bulan', 'desc')
                    ->first();
    }

    // ========================================
    // ACCESSOR EXISTING (Jangan diubah)
    // ========================================

    public function getNamaLengkapAttribute()
    {
        return $this->gelar ? $this->name . ', ' . $this->gelar : $this->name;
    }

    public function getUmurAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : null;
    }
}