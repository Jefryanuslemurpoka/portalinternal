<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\CutiIzin;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Helpers\NotificationHelper;

class AbsensiController extends Controller
{
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');

        $today = Carbon::today('Asia/Jakarta');
        $user  = Auth::user();

        // ✅ Cek cuti / izin aktif hari ini
        $cutiIzinHariIni = CutiIzin::where('user_id', $user->id)
            ->where('status', 'disetujui')
            ->whereDate('tanggal_mulai', '<=', $today->format('Y-m-d'))
            ->whereDate('tanggal_selesai', '>=', $today->format('Y-m-d'))
            ->first();

        // ✅ Cek absensi hari ini
        $absensiHariIni = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today->format('Y-m-d'))
            ->first();

        // ✅ Auto create absensi cuti / izin
        if ($cutiIzinHariIni && !$absensiHariIni) {
            $absensiHariIni = Absensi::create([
                'user_id'       => $user->id,
                'tanggal'      => $today->format('Y-m-d'),
                'jam_masuk'    => null,
                'jam_keluar'   => null,
                'lokasi'       => null,
                'foto'         => null,
                'status'       => $cutiIzinHariIni->jenis,
                'keterangan'  => 'Auto-generated dari pengajuan ' . $cutiIzinHariIni->jenis,
                'cuti_izin_id'=> $cutiIzinHariIni->id,
            ]);
        }

        $jamMasuk = Setting::get('jam_masuk', '08:00');
        $jamKeluar = Setting::get('jam_keluar', '17:00');
        $toleransi = Setting::get('toleransi_keterlambatan', 15);

        $validasiRadiusAktif = Setting::get('validasi_radius_aktif', true);

        $lokasiKantor = [
            'nama'      => Setting::get('lokasi_kantor_nama', 'Kantor Pusat'),
            'latitude' => Setting::get('lokasi_kantor_latitude', '-6.200000'),
            'longitude'=> Setting::get('lokasi_kantor_longitude', '106.816666'),
            'radius'   => Setting::get('lokasi_kantor_radius', 100),
        ];

        return view('karyawan.absensi.index', compact(
            'absensiHariIni',
            'cutiIzinHariIni',
            'jamMasuk',
            'jamKeluar',
            'toleransi',
            'lokasiKantor',
            'validasiRadiusAktif'
        ));
    }

    public function checkIn(Request $request)
    {
        try {

            date_default_timezone_set('Asia/Jakarta');

            $request->validate([
                'latitude'  => 'required|numeric',
                'longitude' => 'required|numeric'
            ]);

            $user  = Auth::user();
            $today= Carbon::today('Asia/Jakarta');
            $now  = Carbon::now('Asia/Jakarta');

            $existingAbsensi = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', $today->format('Y-m-d'))
                ->first();

            if ($existingAbsensi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah check-in hari ini'
                ], 400);
            }

            $lokasiKantor = [
                'latitude' => Setting::get('lokasi_kantor_latitude', '-6.200000'),
                'longitude'=> Setting::get('lokasi_kantor_longitude', '106.816666'),
                'radius'   => Setting::get('lokasi_kantor_radius', 100),
            ];

            $validasiRadiusAktif = Setting::get('validasi_radius_aktif', true);

            if ($validasiRadiusAktif && ($request->latitude != 0 || $request->longitude != 0)) {

                $distance = $this->calculateDistance(
                    $request->latitude,
                    $request->longitude,
                    $lokasiKantor['latitude'],
                    $lokasiKantor['longitude']
                );

                if ($distance > $lokasiKantor['radius']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda di luar radius kantor (' . round($distance) . ' m)'
                    ], 400);
                }
            }

            $absensi = Absensi::create([
                'user_id'   => $user->id,
                'tanggal'  => $today->format('Y-m-d'),
                'jam_masuk'=> $now->format('H:i:s'),
                'lokasi'   => $request->latitude . ',' . $request->longitude,
                'status'   => 'Hadir'
            ]);

            if (class_exists(NotificationHelper::class)) {
                NotificationHelper::create(
                    $user->id,
                    'absensi',
                    'Check-in Berhasil',
                    'Check-in: ' . $now->format('d M Y H:i'),
                    route('karyawan.absensi.index')
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Check-in berhasil',
                'data'    => $absensi
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ], 500);

        }
    }

    public function checkOut(Request $request)
    {
        try {

            date_default_timezone_set('Asia/Jakarta');

            $request->validate([
                'latitude'  => 'required|numeric',
                'longitude' => 'required|numeric'
            ]);

            $user  = Auth::user();
            $today= Carbon::today('Asia/Jakarta');
            $now  = Carbon::now('Asia/Jakarta');

            $absensi = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', $today->format('Y-m-d'))
                ->first();

            if (!$absensi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum check-in'
                ], 400);
            }

            if ($absensi->jam_keluar) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sudah check-out'
                ], 400);
            }

            $absensi->update([
                'jam_keluar' => $now->format('H:i:s'),
            ]);

            if (class_exists(NotificationHelper::class)) {
                NotificationHelper::create(
                    $user->id,
                    'absensi',
                    'Check-out Berhasil',
                    'Check-out: ' . $now->format('d M Y H:i'),
                    route('karyawan.absensi.index')
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Check-out berhasil',
                'data'    => $absensi
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ], 500);

        }
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $latDelta = $lat2 - $lat1;
        $lonDelta = $lon2 - $lon1;

        $angle = 2 * asin(
            sqrt(
                pow(sin($latDelta / 2), 2) +
                cos($lat1) * cos($lat2) *
                pow(sin($lonDelta / 2), 2)
            )
        );

        return $angle * $earthRadius;
    }

    public function today()
    {
        $today = Carbon::today('Asia/Jakarta');
        $user  = Auth::user();

        $absensi = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today->format('Y-m-d'))
            ->first();

        return response()->json([
            'success' => true,
            'data' => $absensi
        ]);
    }
}
