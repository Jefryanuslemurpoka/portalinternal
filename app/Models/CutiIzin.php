<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutiIzin extends Model
{
    use HasFactory;

    protected $table = 'cuti_izin';

    protected $fillable = [
        'user_id',
        'jenis',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'status',
        'approved_by',
        'keterangan_approval',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Relasi ke User (pengaju)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke User (yang approve)
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Status Checker
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
}