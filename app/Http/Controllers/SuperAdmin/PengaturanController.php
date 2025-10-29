<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\LogAktivitas;

class PengaturanController extends Controller
{
    // Tampilkan halaman pengaturan
    public function index()
    {
        // Ambil pengaturan dari cache atau default
        $jamMasuk = Cache::get('jam_masuk', '08:00');
        $jamKeluar = Cache::get('jam_keluar', '17:00');
        $toleransiKeterlambatan = Cache::get('toleransi_keterlambatan', 15); // menit
        
        $lokasiKantor = Cache::get('lokasi_kantor', [
            'nama' => 'Kantor Pusat',
            'alamat' => 'Jl. Contoh No. 123, Jakarta',
            'latitude' => '-6.200000',
            'longitude' => '106.816666',
            'radius' => 100, // meter
        ]);

        // Ambil status validasi radius (default: aktif/true)
        $validasiRadiusAktif = Cache::get('validasi_radius_aktif', true);

        $namaPerusahaan = Cache::get('nama_perusahaan', 'Portal Internal');
        $emailPerusahaan = Cache::get('email_perusahaan', 'info@portal.com');
        $teleponPerusahaan = Cache::get('telepon_perusahaan', '021-12345678');

        return view('superadmin.pengaturan.index', compact(
            'jamMasuk',
            'jamKeluar',
            'toleransiKeterlambatan',
            'lokasiKantor',
            'validasiRadiusAktif',
            'namaPerusahaan',
            'emailPerusahaan',
            'teleponPerusahaan'
        ));
    }

    // Update Jam Kerja
    public function updateJamKerja(Request $request)
    {
        $request->validate([
            'jam_masuk' => 'required|date_format:H:i',
            'jam_keluar' => 'required|date_format:H:i|after:jam_masuk',
            'toleransi_keterlambatan' => 'required|integer|min:0|max:60',
        ], [
            'jam_masuk.required' => 'Jam masuk harus diisi',
            'jam_masuk.date_format' => 'Format jam masuk tidak valid',
            'jam_keluar.required' => 'Jam keluar harus diisi',
            'jam_keluar.date_format' => 'Format jam keluar tidak valid',
            'jam_keluar.after' => 'Jam keluar harus setelah jam masuk',
            'toleransi_keterlambatan.required' => 'Toleransi keterlambatan harus diisi',
            'toleransi_keterlambatan.integer' => 'Toleransi harus berupa angka',
            'toleransi_keterlambatan.min' => 'Toleransi minimal 0 menit',
            'toleransi_keterlambatan.max' => 'Toleransi maksimal 60 menit',
        ]);

        // Simpan ke cache (permanent)
        Cache::forever('jam_masuk', $request->jam_masuk);
        Cache::forever('jam_keluar', $request->jam_keluar);
        Cache::forever('toleransi_keterlambatan', $request->toleransi_keterlambatan);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Update Jam Kerja',
            'deskripsi' => "Mengubah jam kerja menjadi {$request->jam_masuk} - {$request->jam_keluar} (Toleransi: {$request->toleransi_keterlambatan} menit)",
        ]);

        return redirect()->route('superadmin.pengaturan.index')
            ->with('success', 'Pengaturan jam kerja berhasil diperbarui');
    }

    // Update Lokasi Kantor
    public function updateLokasi(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'alamat' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|integer|min:10|max:1000',
        ], [
            'nama_lokasi.required' => 'Nama lokasi harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'latitude.required' => 'Latitude harus diisi',
            'latitude.numeric' => 'Latitude harus berupa angka',
            'latitude.between' => 'Latitude harus antara -90 sampai 90',
            'longitude.required' => 'Longitude harus diisi',
            'longitude.numeric' => 'Longitude harus berupa angka',
            'longitude.between' => 'Longitude harus antara -180 sampai 180',
            'radius.required' => 'Radius harus diisi',
            'radius.integer' => 'Radius harus berupa angka',
            'radius.min' => 'Radius minimal 10 meter',
            'radius.max' => 'Radius maksimal 1000 meter',
        ]);

        $lokasiKantor = [
            'nama' => $request->nama_lokasi,
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius,
        ];

        // Simpan lokasi ke cache
        Cache::forever('lokasi_kantor', $lokasiKantor);

        // Simpan status validasi radius GPS (true jika checkbox dicentang, false jika tidak)
        $validasiRadiusAktif = $request->has('validasi_radius_aktif') ? true : false;
        Cache::forever('validasi_radius_aktif', $validasiRadiusAktif);

        // Log aktivitas
        $statusValidasi = $validasiRadiusAktif ? 'Aktif' : 'Nonaktif';
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Update Lokasi Kantor',
            'deskripsi' => "Mengubah lokasi kantor: {$request->nama_lokasi} (Radius: {$request->radius}m, Validasi GPS: {$statusValidasi})",
        ]);

        return redirect()->route('superadmin.pengaturan.index')
            ->with('success', 'Pengaturan lokasi kantor berhasil diperbarui');
    }

    // Update Informasi Umum
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'email_perusahaan' => 'required|email',
            'telepon_perusahaan' => 'required|string|max:20',
        ], [
            'nama_perusahaan.required' => 'Nama perusahaan harus diisi',
            'email_perusahaan.required' => 'Email perusahaan harus diisi',
            'email_perusahaan.email' => 'Format email tidak valid',
            'telepon_perusahaan.required' => 'Telepon perusahaan harus diisi',
        ]);

        // Simpan ke cache
        Cache::forever('nama_perusahaan', $request->nama_perusahaan);
        Cache::forever('email_perusahaan', $request->email_perusahaan);
        Cache::forever('telepon_perusahaan', $request->telepon_perusahaan);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Update Info Perusahaan',
            'deskripsi' => "Mengubah informasi perusahaan: {$request->nama_perusahaan}",
        ]);

        return redirect()->route('superadmin.pengaturan.index')
            ->with('success', 'Informasi perusahaan berhasil diperbarui');
    }

    // Method helper untuk mengecek apakah validasi radius aktif (bisa dipanggil dari controller lain)
    public static function isValidasiRadiusAktif()
    {
        return Cache::get('validasi_radius_aktif', true);
    }

    // Method helper untuk mendapatkan lokasi kantor (bisa dipanggil dari controller lain)
    public static function getLokasiKantor()
    {
        return Cache::get('lokasi_kantor', [
            'nama' => 'Kantor Pusat',
            'alamat' => 'Jl. Contoh No. 123, Jakarta',
            'latitude' => '-6.200000',
            'longitude' => '106.816666',
            'radius' => 100,
        ]);
    }
}