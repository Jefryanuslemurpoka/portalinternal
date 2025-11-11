<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;

    protected $table = 'divisi';

    protected $fillable = [
        'nama_divisi',
        'deskripsi',
        'status',
    ];

    // Relasi dengan User
    public function users()
    {
        return $this->hasMany(User::class, 'divisi', 'nama_divisi');
    }

    // Scope untuk divisi aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}