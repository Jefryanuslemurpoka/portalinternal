<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanNotarisDetail extends Model
{
    use HasFactory;

    protected $table = 'laporan_notaris_detail';

    protected $fillable = [
        'laporan_notaris_id',
        'tanggal',
        'gamal',
        'eny',
        'nyoman',
        'otty',
        'kartika',
        'retno',
        'neltje',
        'total',
        'is_libur',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'is_libur' => 'boolean',
    ];

    // Relasi ke Laporan Utama
    public function laporan()
    {
        return $this->belongsTo(LaporanNotaris::class, 'laporan_notaris_id');
    }

    // Auto Calculate Total saat saving
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($detail) {
            $detail->total = 
                $detail->gamal + 
                $detail->eny + 
                $detail->nyoman + 
                $detail->otty + 
                $detail->kartika + 
                $detail->retno + 
                $detail->neltje;
        });
    }
}