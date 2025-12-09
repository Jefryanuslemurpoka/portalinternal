<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanNotaris extends Model
{
    use HasFactory;

    protected $table = 'laporan_notaris';

    protected $fillable = [
        'bulan',
        'tahun',
        'nama_bulan',
        'created_by',
        'total_gamal',
        'total_eny',
        'total_nyoman',
        'total_otty',
        'total_kartika',
        'total_retno',
        'total_neltje',
        'grand_total',
    ];

    protected $casts = [
        'tahun' => 'integer',
    ];

    // Relasi ke User
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke Detail Harian
    public function details()
    {
        return $this->hasMany(LaporanNotarisDetail::class, 'laporan_notaris_id');
    }

    // Helper: Get Nama Notaris List
    public static function getNotarisList()
    {
        return [
            'gamal' => 'Gamal Abdul Nasir',
            'eny' => 'Eny',
            'nyoman' => 'Nyoman',
            'otty' => 'Otty',
            'kartika' => 'Kartika',
            'retno' => 'Retno',
            'neltje' => 'Neltje',
        ];
    }
}