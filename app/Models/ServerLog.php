<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerLog extends Model
{
    use HasFactory;

    protected $table = 'server_log';

    protected $fillable = [
        'tanggal',
        'aktivitas',
        'status',
        'deskripsi',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Status Checker
    public function isNormal()
    {
        return $this->status === 'normal';
    }

    public function isWarning()
    {
        return $this->status === 'warning';
    }

    public function isError()
    {
        return $this->status === 'error';
    }
}