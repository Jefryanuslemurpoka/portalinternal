<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'divisi',
        'foto',
        'status',
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
        ];
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
}