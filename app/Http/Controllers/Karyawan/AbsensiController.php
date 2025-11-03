<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Helpers\NotificationHelper; // ✅ TAMBAHAN (OPSIONAL)

class AbsensiController extends Controller
{
    // Tampilkan halaman absensi
    public function index()
    {
        // Set timezone ke Asia/Jakarta
        date_default_timezone_set('Asia/Jakarta');
        
        $today = Carbon::today('Asia/Jakarta');
        $user = Auth::user();

        // Cek absensi hari ini
        $absensiHariIni = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        // ✅ Ambil Jam Kerja dari Model Setting
        $jamMasuk = Setting::get('jam_masuk', '08:00');
        $jamKeluar = Setting::get('jam_keluar', '17:00');
        $toleransi = Setting::get('toleransi_keterlambatan', 15);

        // ✅ Ambil Lokasi Kantor dari Model Setting
        $lokasiKantor = [
            'nama' => Setting::get('lokasi_kantor_nama', 'Kantor Pusat'),
            'latitude' => Setting::get('lokasi_kantor_latitude', '-6.200000'),
            'longitude' => Setting::get('lokasi_kantor_longitude', '106.816666'),
            'radius' => Setting::get('lokasi_kantor_radius', 100),
        ];

        return view('karyawan.absensi.index', compact(
            'absensiHariIni',
            'jamMasuk',
            'jamKeluar',
            'toleransi',
            'lokasiKantor'
        ));
    }

    // Check-in
    public function checkIn(Request $request)
    {
        // Set timezone ke Asia/Jakarta
        date_default_timezone_set('Asia/Jakarta');
        
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = Auth::user();
        $today = Carbon::today('Asia/Jakarta');
        $now = Carbon::now('Asia/Jakarta');

        // Cek apakah sudah check-in hari ini
        $existingAbsensi = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        if ($existingAbsensi) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan check-in hari ini'
            ], 400);
        }

        // ✅ Ambil pengaturan lokasi dari Model Setting
        $lokasiKantor = [
            'latitude' => Setting::get('lokasi_kantor_latitude', '-6.200000'),
            'longitude' => Setting::get('lokasi_kantor_longitude', '106.816666'),
            'radius' => Setting::get('lokasi_kantor_radius', 100),
        ];

        // ✅ Cek apakah validasi radius aktif
        $validasiRadiusAktif = Setting::get('validasi_radius_aktif', true);

        // Hitung jarak
        $distance = $this->calculateDistance(
            $request->latitude,
            $request->longitude,
            $lokasiKantor['latitude'],
            $lokasiKantor['longitude']
        );

        // Validasi radius hanya jika fitur aktif
        if ($validasiRadiusAktif && $distance > $lokasiKantor['radius']) {
            return response()->json([
                'success' => false,
                'message' => 'Anda berada di luar radius kantor (' . round($distance) . 'm). Radius maksimal: ' . $lokasiKantor['radius'] . 'm'
            ], 400);
        }

        // Buat absensi
        $absensi = Absensi::create([
            'user_id' => $user->id,
            'tanggal' => $today->format('Y-m-d'),
            'jam_masuk' => $now->format('H:i:s'),
            'lokasi' => $request->latitude . ', ' . $request->longitude,
            'status' => 'Hadir',
        ]);

        // ✅ TAMBAHAN (OPSIONAL): Kirim notifikasi konfirmasi ke karyawan sendiri
        if (class_exists('App\Helpers\NotificationHelper')) {
            NotificationHelper::create(
                auth()->id(),
                'absensi',
                'Check-in Berhasil',
                'Anda telah check-in pada ' . $now->format('d M Y H:i'),
                route('karyawan.absensi.index')
            );
        }

        $message = 'Check-in berhasil pada ' . $now->format('H:i:s');
        if (!$validasiRadiusAktif) {
            $message .= ' (Mode WFH - validasi lokasi nonaktif)';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $absensi
        ]);
    }

    // Check-out
    public function checkOut(Request $request)
    {
        // Set timezone ke Asia/Jakarta
        date_default_timezone_set('Asia/Jakarta');
        
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = Auth::user();
        $today = Carbon::today('Asia/Jakarta');
        $now = Carbon::now('Asia/Jakarta');

        // Cek absensi hari ini
        $absensi = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absensi) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum melakukan check-in hari ini'
            ], 400);
        }

        if ($absensi->jam_keluar) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan check-out hari ini'
            ], 400);
        }

        // ✅ Ambil pengaturan lokasi dari Model Setting
        $lokasiKantor = [
            'latitude' => Setting::get('lokasi_kantor_latitude', '-6.200000'),
            'longitude' => Setting::get('lokasi_kantor_longitude', '106.816666'),
            'radius' => Setting::get('lokasi_kantor_radius', 100),
        ];

        // ✅ Cek apakah validasi radius aktif
        $validasiRadiusAktif = Setting::get('validasi_radius_aktif', true);

        // Hitung jarak
        $distance = $this->calculateDistance(
            $request->latitude,
            $request->longitude,
            $lokasiKantor['latitude'],
            $lokasiKantor['longitude']
        );

        // Validasi radius hanya jika fitur aktif
        if ($validasiRadiusAktif && $distance > $lokasiKantor['radius']) {
            return response()->json([
                'success' => false,
                'message' => 'Anda berada di luar radius kantor (' . round($distance) . 'm). Radius maksimal: ' . $lokasiKantor['radius'] . 'm'
            ], 400);
        }

        // Update jam keluar
        $absensi->update([
            'jam_keluar' => $now->format('H:i:s'),
        ]);

        // ✅ TAMBAHAN (OPSIONAL): Kirim notifikasi konfirmasi ke karyawan sendiri
        if (class_exists('App\Helpers\NotificationHelper')) {
            NotificationHelper::create(
                auth()->id(),
                'absensi',
                'Check-out Berhasil',
                'Anda telah check-out pada ' . $now->format('d M Y H:i'),
                route('karyawan.absensi.index')
            );
        }

        $message = 'Check-out berhasil pada ' . $now->format('H:i:s');
        if (!$validasiRadiusAktif) {
            $message .= ' (Mode WFH - validasi lokasi nonaktif)';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $absensi
        ]);
    }

    // Hitung jarak antara 2 koordinat (Haversine formula)
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meter

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }

    // Get absensi today (untuk AJAX)
    public function today()
    {
        $today = Carbon::today('Asia/Jakarta');
        $user = Auth::user();

        $absensi = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        return response()->json([
            'success' => true,
            'data' => $absensi
        ]);
    }
}