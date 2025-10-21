<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Surat extends Model
{
    use HasFactory;

    protected $table = 'surat';

    protected $fillable = [
        'nomor_surat',
        'jenis',
        'tanggal',
        'pengirim',
        'penerima',
        'perihal',
        'file_path',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // File Handling Methods
    public function getFileUrlAttribute()
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    public function deleteFile()
    {
        if ($this->file_path && Storage::exists($this->file_path)) {
            Storage::delete($this->file_path);
        }
    }

    // Override delete untuk hapus file juga
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($surat) {
            $surat->deleteFile();
        });
    }
}