<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Models\LogAktivitas;

class PengaturanController extends Controller
{
    // Tampilkan halaman pengaturan
    public function index()
    {
        // Jam Kerja
        $jamMasuk = Cache::get('jam_masuk', '08:00');
        $jamKeluar = Cache::get('jam_keluar', '17:00');
        $toleransiKeterlambatan = (int) Cache::get('toleransi_keterlambatan', 15); // Cast ke integer

        // Lokasi Kantor
        $lokasiKantor = Cache::get('lokasi_kantor', [
            'nama' => 'Kantor Pusat',
            'alamat' => 'Jl. Contoh No. 123, Jakarta',
            'latitude' => '-6.200000',
            'longitude' => '106.816666',
            'radius' => 100,
        ]);

        // Validasi Radius GPS (default: true/aktif)
        $validasiRadiusAktif = Cache::get('validasi_radius_aktif', true);

        // Info Perusahaan
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
        $validator = Validator::make($request->all(), [
            'jam_masuk' => 'required|date_format:H:i',
            'jam_keluar' => 'required|date_format:H:i|after:jam_masuk',
            'toleransi_keterlambatan' => 'required|integer|min:0|max:60',
        ], [
            'jam_masuk.required' => 'Jam masuk harus diisi',
            'jam_masuk.date_format' => 'Format jam masuk tidak valid',
            'jam_keluar.required' => 'Jam keluar harus diisi',
            'jam_keluar.date_format' => 'Format jam keluar tidak valid',
            'jam_keluar.after' => 'Jam keluar harus lebih besar dari jam masuk',
            'toleransi_keterlambatan.required' => 'Toleransi keterlambatan harus diisi',
            'toleransi_keterlambatan.integer' => 'Toleransi harus berupa angka',
            'toleransi_keterlambatan.min' => 'Toleransi minimal 0 menit',
            'toleransi_keterlambatan.max' => 'Toleransi maksimal 60 menit',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Simpan ke cache (permanent) - pastikan toleransi dalam format integer
        Cache::forever('jam_masuk', $request->jam_masuk);
        Cache::forever('jam_keluar', $request->jam_keluar);
        Cache::forever('toleransi_keterlambatan', (int) $request->toleransi_keterlambatan); // Cast ke integer

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Update Jam Kerja',
            'deskripsi' => "Mengubah jam kerja menjadi {$request->jam_masuk} - {$request->jam_keluar} (Toleransi: {$request->toleransi_keterlambatan} menit)",
        ]);

        return redirect()->route('superadmin.pengaturan.index')
            ->with('success', 'Pengaturan jam kerja berhasil disimpan!');
    }

    // Update Lokasi Kantor
    public function updateLokasi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lokasi' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|integer|min:10|max:1000',
        ], [
            'nama_lokasi.required' => 'Nama lokasi harus diisi',
            'nama_lokasi.max' => 'Nama lokasi maksimal 255 karakter',
            'alamat.required' => 'Alamat harus diisi',
            'alamat.max' => 'Alamat maksimal 500 karakter',
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

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Simpan lokasi kantor
        $lokasiKantor = [
            'nama' => $request->nama_lokasi,
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => (int) $request->radius, // Cast ke integer
        ];

        Cache::forever('lokasi_kantor', $lokasiKantor);

        // Simpan status validasi radius (true jika checkbox dicentang, false jika tidak)
        $validasiRadiusAktif = $request->has('validasi_radius_aktif') ? true : false;
        Cache::forever('validasi_radius_aktif', $validasiRadiusAktif);

        // Log aktivitas
        $statusValidasi = $validasiRadiusAktif ? 'Aktif' : 'Nonaktif';
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Update Lokasi Kantor',
            'deskripsi' => "Mengubah lokasi kantor: {$request->nama_lokasi} (Radius: {$request->radius}m, Validasi GPS: {$statusValidasi})",
        ]);

        $message = "Pengaturan lokasi berhasil disimpan! Validasi radius GPS: {$statusValidasi}.";
        
        return redirect()->route('superadmin.pengaturan.index')
            ->with('success', $message);
    }

    // Update Info Perusahaan
    public function updateGeneral(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_perusahaan' => 'required|string|max:255',
            'email_perusahaan' => 'required|email|max:255',
            'telepon_perusahaan' => 'required|string|max:20',
        ], [
            'nama_perusahaan.required' => 'Nama perusahaan harus diisi',
            'nama_perusahaan.max' => 'Nama perusahaan maksimal 255 karakter',
            'email_perusahaan.required' => 'Email perusahaan harus diisi',
            'email_perusahaan.email' => 'Format email tidak valid',
            'email_perusahaan.max' => 'Email maksimal 255 karakter',
            'telepon_perusahaan.required' => 'Telepon perusahaan harus diisi',
            'telepon_perusahaan.max' => 'Telepon maksimal 20 karakter',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

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
            ->with('success', 'Informasi perusahaan berhasil disimpan!');
    }

    // Helper method untuk cek validasi radius (bisa dipanggil dari controller lain)
    public static function isValidasiRadiusAktif()
    {
        return Cache::get('validasi_radius_aktif', true);
    }

    // Helper method untuk get lokasi kantor (bisa dipanggil dari controller lain)
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