<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengumuman;
use App\Models\User;
use Carbon\Carbon;

class PengumumanSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil Super Admin sebagai creator
        $admin = User::where('role', 'super_admin')->first();

        // Pengumuman 1
        Pengumuman::create([
            'judul' => 'Libur Nasional - Hari Raya Idul Fitri',
            'konten' => 'Diberitahukan kepada seluruh karyawan bahwa tanggal 10-14 April 2025 adalah libur nasional Idul Fitri. Kantor akan beroperasi kembali pada tanggal 15 April 2025. Selamat Hari Raya Idul Fitri, Mohon Maaf Lahir dan Batin.',
            'tanggal' => Carbon::now()->subDays(5),
            'created_by' => $admin->id,
        ]);

        // Pengumuman 2
        Pengumuman::create([
            'judul' => 'Update Sistem Absensi',
            'konten' => 'Mulai tanggal 1 Mei 2025, sistem absensi akan menggunakan face recognition dan GPS tracking. Mohon seluruh karyawan untuk mengupdate foto profil dan mengaktifkan lokasi pada perangkat mobile masing-masing.',
            'tanggal' => Carbon::now()->subDays(3),
            'created_by' => $admin->id,
        ]);

        // Pengumuman 3
        Pengumuman::create([
            'judul' => 'Rapat Koordinasi Bulanan',
            'konten' => 'Rapat koordinasi bulanan akan dilaksanakan pada hari Jumat, 25 Oktober 2025 pukul 09.00 WIB di ruang meeting lantai 3. Kehadiran seluruh karyawan wajib. Agenda: Evaluasi kinerja bulan September dan target bulan Oktober.',
            'tanggal' => Carbon::now()->subDay(),
            'created_by' => $admin->id,
        ]);

        // Pengumuman 4
        Pengumuman::create([
            'judul' => 'Program Kesehatan Karyawan',
            'konten' => 'Perusahaan akan mengadakan program medical check-up gratis untuk seluruh karyawan pada tanggal 5-7 November 2025. Pendaftaran dapat dilakukan melalui HRD mulai tanggal 28 Oktober 2025.',
            'tanggal' => Carbon::now(),
            'created_by' => $admin->id,
        ]);

        // Pengumuman 5
        Pengumuman::create([
            'judul' => 'Pelatihan Digital Marketing',
            'konten' => 'HRD akan mengadakan pelatihan Digital Marketing untuk meningkatkan skill karyawan. Pelatihan akan dilaksanakan selama 3 hari (12-14 November 2025). Peserta terbatas 20 orang, pendaftaran first come first served.',
            'tanggal' => Carbon::now(),
            'created_by' => $admin->id,
        ]);
    }
}