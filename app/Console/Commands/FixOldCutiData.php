<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CutiIzin;
use Carbon\Carbon;

class FixOldCutiData extends Command
{
    protected $signature = 'cuti:fix-old-data';
    protected $description = 'Fix old cuti data (jumlah_hari & tanggal_pengajuan)';

    public function handle()
    {
        $this->info('🔧 Memperbaiki data lama...');

        $fixed = 0;
        
        CutiIzin::chunk(100, function ($cutiList) use (&$fixed) {
            foreach ($cutiList as $cuti) {
                $needUpdate = false;

                // Fix jumlah_hari
                if (empty($cuti->jumlah_hari) || $cuti->jumlah_hari == 0) {
                    $cuti->jumlah_hari = Carbon::parse($cuti->tanggal_mulai)->diffInDays(Carbon::parse($cuti->tanggal_selesai)) + 1;
                    $needUpdate = true;
                }

                // Fix tanggal_pengajuan
                if (empty($cuti->tanggal_pengajuan) || Carbon::parse($cuti->tanggal_pengajuan)->year < 2020) {
                    $cuti->tanggal_pengajuan = $cuti->created_at;
                    $needUpdate = true;
                }

                if ($needUpdate) {
                    $cuti->save();
                    $fixed++;
                }
            }
        });

        $this->info("✅ Berhasil memperbaiki {$fixed} data!");
        
        return 0;
    }
}