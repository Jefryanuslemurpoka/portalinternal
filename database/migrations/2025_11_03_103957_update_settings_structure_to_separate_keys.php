<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ✅ Hapus data lama jika ada (lokasi_kantor dalam format JSON)
        DB::table('settings')->where('key', 'lokasi_kantor')->delete();

        // ✅ Insert/Update settings HANYA jika belum ada
        $settings = [
            // Jam Kerja
            ['key' => 'jam_masuk', 'value' => '08:00'],
            ['key' => 'jam_keluar', 'value' => '17:00'],
            ['key' => 'toleransi_keterlambatan', 'value' => '15'],
            
            // Lokasi Kantor (dipisah per field)
            ['key' => 'lokasi_kantor_nama', 'value' => 'Kantor Pusat'],
            ['key' => 'lokasi_kantor_alamat', 'value' => 'Jl. Contoh No. 123, Jakarta'],
            ['key' => 'lokasi_kantor_latitude', 'value' => '-6.200000'],
            ['key' => 'lokasi_kantor_longitude', 'value' => '106.816666'],
            ['key' => 'lokasi_kantor_radius', 'value' => '100'],
            
            // Validasi GPS (1 = aktif, 0 = nonaktif)
            ['key' => 'validasi_radius_aktif', 'value' => '1'],
            
            // Info Perusahaan
            ['key' => 'nama_perusahaan', 'value' => 'Portal Internal'],
            ['key' => 'email_perusahaan', 'value' => 'info@portal.com'],
            ['key' => 'telepon_perusahaan', 'value' => '021-12345678'],
        ];

        foreach ($settings as $setting) {
            // ✅ CEK DULU: Insert hanya jika key belum ada
            $exists = DB::table('settings')->where('key', $setting['key'])->exists();
            
            if (!$exists) {
                DB::table('settings')->insert([
                    'key' => $setting['key'],
                    'value' => $setting['value'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // ✅ Clear cache untuk setting
        if (function_exists('cache')) {
            cache()->flush();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ✅ Hapus settings baru
        $keysToDelete = [
            'jam_masuk',
            'jam_keluar',
            'toleransi_keterlambatan',
            'lokasi_kantor_nama',
            'lokasi_kantor_alamat',
            'lokasi_kantor_latitude',
            'lokasi_kantor_longitude',
            'lokasi_kantor_radius',
            'validasi_radius_aktif',
            'nama_perusahaan',
            'email_perusahaan',
            'telepon_perusahaan',
        ];

        DB::table('settings')->whereIn('key', $keysToDelete)->delete();

        // ✅ Restore lokasi_kantor dalam format JSON (rollback)
        DB::table('settings')->updateOrInsert(
            ['key' => 'lokasi_kantor'],
            [
                'value' => json_encode([
                    'nama' => 'Kantor Pusat',
                    'alamat' => 'Jl. Contoh No. 123, Jakarta',
                    'latitude' => '-6.200000',
                    'longitude' => '106.816666',
                    'radius' => 100,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Clear cache
        if (function_exists('cache')) {
            cache()->flush();
        }
    }
};