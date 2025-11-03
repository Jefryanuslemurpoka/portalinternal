<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\LogAktivitas;
use App\Models\Setting;

class PengaturanController extends Controller
{
    // Tampilkan halaman pengaturan
    public function index()
    {
        // Jam Kerja
        $jamMasuk = Setting::get('jam_masuk', '08:00');
        $jamKeluar = Setting::get('jam_keluar', '17:00');
        $toleransiKeterlambatan = (int) Setting::get('toleransi_keterlambatan', 15);

        // ✅ Ambil data lokasi kantor dari Setting terpisah
        $lokasiKantor = [
            'nama' => Setting::get('lokasi_kantor_nama', 'Kantor Pusat'),
            'alamat' => Setting::get('lokasi_kantor_alamat', 'Jl. Contoh No. 123, Jakarta'),
            'latitude' => Setting::get('lokasi_kantor_latitude', '-6.200000'),
            'longitude' => Setting::get('lokasi_kantor_longitude', '106.816666'),
            'radius' => (int) Setting::get('lokasi_kantor_radius', 100),
        ];

        // ✅ Validasi Radius GPS (1 = aktif, 0 = nonaktif)
        $validasiRadiusAktif = (bool) Setting::get('validasi_radius_aktif', 1);

        // Info Perusahaan
        $namaPerusahaan = Setting::get('nama_perusahaan', 'Portal Internal');
        $emailPerusahaan = Setting::get('email_perusahaan', 'info@portal.com');
        $teleponPerusahaan = Setting::get('telepon_perusahaan', '021-12345678');

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

        // Simpan ke database
        Setting::set('jam_masuk', $request->jam_masuk);
        Setting::set('jam_keluar', $request->jam_keluar);
        Setting::set('toleransi_keterlambatan', (int) $request->toleransi_keterlambatan);

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
            'radius' => 'nullable|integer|min:10|max:1000', // ✅ nullable
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
            'radius.integer' => 'Radius harus berupa angka',
            'radius.min' => 'Radius minimal 10 meter',
            'radius.max' => 'Radius maksimal 1000 meter',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // ✅ Simpan lokasi kantor dengan key terpisah
        Setting::set('lokasi_kantor_nama', $request->nama_lokasi);
        Setting::set('lokasi_kantor_alamat', $request->alamat);
        Setting::set('lokasi_kantor_latitude', $request->latitude);
        Setting::set('lokasi_kantor_longitude', $request->longitude);
        Setting::set('lokasi_kantor_radius', (int) $request->radius ?? 100);

        // ✅ Simpan status validasi radius (1 = aktif, 0 = nonaktif)
        $validasiRadiusAktif = $request->has('validasi_radius_aktif') ? 1 : 0;
        Setting::set('validasi_radius_aktif', $validasiRadiusAktif);

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

        // Simpan ke database
        Setting::set('nama_perusahaan', $request->nama_perusahaan);
        Setting::set('email_perusahaan', $request->email_perusahaan);
        Setting::set('telepon_perusahaan', $request->telepon_perusahaan);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Update Info Perusahaan',
            'deskripsi' => "Mengubah informasi perusahaan: {$request->nama_perusahaan}",
        ]);

        return redirect()->route('superadmin.pengaturan.index')
            ->with('success', 'Informasi perusahaan berhasil disimpan!');
    }

    // Helper method untuk cek validasi radius
    public static function isValidasiRadiusAktif()
    {
        return (bool) Setting::get('validasi_radius_aktif', 1);
    }

    // Helper method untuk get lokasi kantor
    public static function getLokasiKantor()
    {
        return [
            'nama' => Setting::get('lokasi_kantor_nama', 'Kantor Pusat'),
            'alamat' => Setting::get('lokasi_kantor_alamat', 'Jl. Contoh No. 123, Jakarta'),
            'latitude' => Setting::get('lokasi_kantor_latitude', '-6.200000'),
            'longitude' => Setting::get('lokasi_kantor_longitude', '106.816666'),
            'radius' => (int) Setting::get('lokasi_kantor_radius', 100),
        ];
    }

    // Helper method untuk get jam kerja
    public static function getJamKerja()
    {
        return [
            'jam_masuk' => Setting::get('jam_masuk', '08:00'),
            'jam_keluar' => Setting::get('jam_keluar', '17:00'),
            'toleransi_keterlambatan' => (int) Setting::get('toleransi_keterlambatan', 15),
        ];
    }
}