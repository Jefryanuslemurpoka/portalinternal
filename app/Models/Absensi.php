<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'user_id',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'lokasi',
        'foto',
        'status',
        'keterangan',
        'cuti_izin_id',
    ];

    protected $casts = [
        'tanggal' => 'date:Y-m-d',
        'jam_masuk' => 'datetime:H:i:s',
        'jam_keluar' => 'datetime:H:i:s',
    ];

    /**
     * ========================
     * RELATIONS
     * ========================
     */

    // ✅ Relasi ke User
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    // ✅ Relasi ke Cuti/Izin
    public function cutiIzin()
    {
        return $this->belongsTo(\App\Models\CutiIzin::class, 'cuti_izin_id');
    }
}
