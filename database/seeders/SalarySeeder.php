<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalarySeeder extends Seeder
{
    public function run(): void
    {
        // Seed Komponen Tunjangan
        $tunjangan = [
            [
                'nama_komponen' => 'Tunjangan BPJS Kesehatan',
                'jenis' => 'tunjangan',
                'tipe_perhitungan' => 'persentase',
                'nilai' => 4.00, // 4% dari gaji pokok
                'is_active' => true,
                'is_taxable' => false,
                'deskripsi' => 'Tunjangan BPJS Kesehatan 4% dari gaji pokok',
            ],
            [
                'nama_komponen' => 'Tunjangan BPJS Ketenagakerjaan',
                'jenis' => 'tunjangan',
                'tipe_perhitungan' => 'persentase',
                'nilai' => 3.70,
                'is_active' => true,
                'is_taxable' => false,
                'deskripsi' => 'Tunjangan BPJS Ketenagakerjaan 3.7%',
            ],
            [
                'nama_komponen' => 'Tunjangan Komunikasi',
                'jenis' => 'tunjangan',
                'tipe_perhitungan' => 'nominal',
                'nilai' => 300000,
                'is_active' => true,
                'is_taxable' => true,
                'deskripsi' => 'Tunjangan pulsa dan komunikasi',
            ],
            [
                'nama_komponen' => 'Tunjangan Keluarga',
                'jenis' => 'tunjangan',
                'tipe_perhitungan' => 'persentase',
                'nilai' => 10.00,
                'is_active' => true,
                'is_taxable' => true,
                'deskripsi' => 'Tunjangan istri/suami 10% dari gaji pokok',
            ],
            [
                'nama_komponen' => 'Tunjangan Anak',
                'jenis' => 'tunjangan',
                'tipe_perhitungan' => 'persentase',
                'nilai' => 2.00,
                'is_active' => true,
                'is_taxable' => true,
                'deskripsi' => 'Tunjangan anak 2% per anak (max 3 anak)',
            ],
        ];

        // Seed Komponen Potongan
        $potongan = [
            [
                'nama_komponen' => 'Potongan BPJS Kesehatan',
                'jenis' => 'potongan',
                'tipe_perhitungan' => 'persentase',
                'nilai' => 1.00, // 1% dari gaji pokok
                'is_active' => true,
                'is_taxable' => false,
                'deskripsi' => 'Iuran BPJS Kesehatan 1% ditanggung karyawan',
            ],
            [
                'nama_komponen' => 'Potongan BPJS Ketenagakerjaan',
                'jenis' => 'potongan',
                'tipe_perhitungan' => 'persentase',
                'nilai' => 2.00,
                'is_active' => true,
                'is_taxable' => false,
                'deskripsi' => 'Iuran BPJS Ketenagakerjaan 2%',
            ],
            [
                'nama_komponen' => 'Potongan PPh 21',
                'jenis' => 'potongan',
                'tipe_perhitungan' => 'persentase',
                'nilai' => 5.00,
                'is_active' => true,
                'is_taxable' => false,
                'deskripsi' => 'Pajak penghasilan pasal 21',
            ],
            [
                'nama_komponen' => 'Potongan Keterlambatan',
                'jenis' => 'potongan',
                'tipe_perhitungan' => 'nominal',
                'nilai' => 50000,
                'is_active' => true,
                'is_taxable' => false,
                'deskripsi' => 'Denda keterlambatan per hari',
            ],
            [
                'nama_komponen' => 'Potongan Alpha',
                'jenis' => 'potongan',
                'tipe_perhitungan' => 'nominal',
                'nilai' => 100000,
                'is_active' => true,
                'is_taxable' => false,
                'deskripsi' => 'Potongan tidak masuk tanpa keterangan',
            ],
            [
                'nama_komponen' => 'Potongan Pinjaman',
                'jenis' => 'potongan',
                'tipe_perhitungan' => 'nominal',
                'nilai' => 0,
                'is_active' => true,
                'is_taxable' => false,
                'deskripsi' => 'Cicilan pinjaman karyawan',
            ],
        ];

        DB::table('salary_components')->insert(array_merge($tunjangan, $potongan));

        // Seed Settings
        $settings = [
            [
                'key' => 'upah_lembur_per_jam',
                'value' => '50000',
                'description' => 'Upah lembur per jam (Rp)',
            ],
            [
                'key' => 'jam_kerja_normal',
                'value' => '8',
                'description' => 'Jam kerja normal per hari',
            ],
            [
                'key' => 'hari_kerja_per_bulan',
                'value' => '22',
                'description' => 'Jumlah hari kerja dalam 1 bulan',
            ],
            [
                'key' => 'toleransi_keterlambatan',
                'value' => '15',
                'description' => 'Toleransi keterlambatan (menit)',
            ],
            [
                'key' => 'auto_generate_slip',
                'value' => '1',
                'description' => 'Auto generate slip gaji setiap akhir bulan (1=Ya, 0=Tidak)',
            ],
            [
                'key' => 'email_notification',
                'value' => '1',
                'description' => 'Kirim email notifikasi slip gaji (1=Ya, 0=Tidak)',
            ],
            [
                'key' => 'ptkp_status',
                'value' => 'TK/0',
                'description' => 'Status PTKP default (TK/0, K/0, K/1, dst)',
            ],
            [
                'key' => 'ptkp_value',
                'value' => '54000000',
                'description' => 'Nilai PTKP per tahun (Rp)',
            ],
        ];

        DB::table('salary_settings')->insert($settings);
    }
}