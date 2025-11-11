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

    // Relasi
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

    /**
     * Relasi ke Notifications
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get unread notifications count
     */
    public function unreadNotificationsCount()
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    /**
     * Get unread notifications (opsional, untuk kemudahan)
     */
    public function unreadNotifications()
    {
        return $this->notifications()->where('is_read', false);
    }

    /**
     * Get nama lengkap dengan gelar
     */
    public function getNamaLengkapAttribute()
    {
        return $this->gelar ? $this->name . ', ' . $this->gelar : $this->name;
    }

    /**
     * Get umur dari tanggal lahir
     */
    public function getUmurAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : null;
    }
}