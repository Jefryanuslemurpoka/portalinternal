<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AbsensiController extends Controller
{
    // Tampilkan halaman absensi
    public function index()
    {
        $today = Carbon::today();
        $user = Auth::user();

        // Cek absensi hari ini
        $absensiHariIni = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        // Jam kerja dari cache
        $jamMasuk = Cache::get('jam_masuk', '08:00');
        $jamKeluar = Cache::get('jam_keluar', '17:00');
        $toleransi = Cache::get('toleransi_keterlambatan', 15);

        // Lokasi kantor dari cache
        $lokasiKantor = Cache::get('lokasi_kantor', [
            'nama' => 'Kantor Pusat',
            'latitude' => '-6.200000',
            'longitude' => '106.816666',
            'radius' => 100,
        ]);

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
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = Auth::user();
        $today = Carbon::today();

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

        // Validasi lokasi (radius)
        $lokasiKantor = Cache::get('lokasi_kantor', [
            'latitude' => '-6.200000',
            'longitude' => '106.816666',
            'radius' => 100,
        ]);
        
        $distance = $this->calculateDistance(
            $request->latitude,
            $request->longitude,
            $lokasiKantor['latitude'],
            $lokasiKantor['longitude']
        );

        if ($distance > $lokasiKantor['radius']) {
            return response()->json([
                'success' => false,
                'message' => 'Anda berada di luar radius kantor (' . round($distance) . 'm). Radius maksimal: ' . $lokasiKantor['radius'] . 'm'
            ], 400);
        }

        // Buat absensi
        $absensi = Absensi::create([
            'user_id' => $user->id,
            'tanggal' => $today,
            'jam_masuk' => Carbon::now()->format('H:i:s'),
            'lokasi' => $request->latitude . ', ' . $request->longitude,
            'status' => 'Hadir',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil pada ' . Carbon::now()->format('H:i:s'),
            'data' => $absensi
        ]);
    }

    // Check-out
    public function checkOut(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = Auth::user();
        $today = Carbon::today();

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

        // Validasi lokasi (radius)
        $lokasiKantor = Cache::get('lokasi_kantor', [
            'latitude' => '-6.200000',
            'longitude' => '106.816666',
            'radius' => 100,
        ]);
        
        $distance = $this->calculateDistance(
            $request->latitude,
            $request->longitude,
            $lokasiKantor['latitude'],
            $lokasiKantor['longitude']
        );

        if ($distance > $lokasiKantor['radius']) {
            return response()->json([
                'success' => false,
                'message' => 'Anda berada di luar radius kantor (' . round($distance) . 'm). Radius maksimal: ' . $lokasiKantor['radius'] . 'm'
            ], 400);
        }

        // Update jam keluar
        $absensi->update([
            'jam_keluar' => Carbon::now()->format('H:i:s'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-out berhasil pada ' . Carbon::now()->format('H:i:s'),
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
        $today = Carbon::today();
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