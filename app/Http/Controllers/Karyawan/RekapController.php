<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class RekapController extends Controller
{
    // Tampilkan halaman rekap
    public function index(Request $request)
    {
        $user = Auth::user();
        $periode = $request->get('periode', 'bulanan'); // harian, mingguan, bulanan
        
        // Jam kerja dari cache
        $jamMasuk = Cache::get('jam_masuk', '08:00');

        // Get data based on periode
        if ($periode === 'harian') {
            $absensi = $this->getHarian($user->id);
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        } elseif ($periode === 'mingguan') {
            $absensi = $this->getMingguan($user->id);
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        } else {
            $absensi = $this->getBulanan($user->id);
            $startDate = Carbon::now()->subMonths(5)->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        }

        // Statistik
        $totalHadir = $absensi->count();
        $tepatWaktu = $absensi->where('jam_masuk', '<=', $jamMasuk . ':00')->count();
        $terlambat = $absensi->where('jam_masuk', '>', $jamMasuk . ':00')->count();

        return view('karyawan.rekap.index', compact(
            'absensi',
            'periode',
            'totalHadir',
            'tepatWaktu',
            'terlambat',
            'jamMasuk',
            'startDate',
            'endDate'
        ));
    }

    // Rekap Harian (bulan ini)
    public function harian(Request $request)
    {
        $user = Auth::user();
        $jamMasuk = Cache::get('jam_masuk', '08:00');

        $absensi = $this->getHarian($user->id);

        $totalHadir = $absensi->count();
        $tepatWaktu = $absensi->where('jam_masuk', '<=', $jamMasuk . ':00')->count();
        $terlambat = $absensi->where('jam_masuk', '>', $jamMasuk . ':00')->count();

        return view('karyawan.rekap.harian', compact(
            'absensi',
            'totalHadir',
            'tepatWaktu',
            'terlambat',
            'jamMasuk'
        ));
    }

    // Rekap Mingguan (4 minggu terakhir)
    public function mingguan(Request $request)
    {
        $user = Auth::user();
        $jamMasuk = Cache::get('jam_masuk', '08:00');

        $absensi = $this->getMingguan($user->id);

        $totalHadir = $absensi->count();
        $tepatWaktu = $absensi->where('jam_masuk', '<=', $jamMasuk . ':00')->count();
        $terlambat = $absensi->where('jam_masuk', '>', $jamMasuk . ':00')->count();

        return view('karyawan.rekap.mingguan', compact(
            'absensi',
            'totalHadir',
            'tepatWaktu',
            'terlambat',
            'jamMasuk'
        ));
    }

    // Rekap Bulanan (6 bulan terakhir)
    public function bulanan(Request $request)
    {
        $user = Auth::user();

        $rekapBulanan = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $absensi = Absensi::where('user_id', $user->id)
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->get();

            $jamMasuk = Cache::get('jam_masuk', '08:00');

            $rekapBulanan[] = [
                'bulan' => $date->format('F Y'),
                'bulan_short' => $date->format('M Y'),
                'total_hadir' => $absensi->count(),
                'tepat_waktu' => $absensi->where('jam_masuk', '<=', $jamMasuk . ':00')->count(),
                'terlambat' => $absensi->where('jam_masuk', '>', $jamMasuk . ':00')->count(),
            ];
        }

        return view('karyawan.rekap.bulanan', compact('rekapBulanan'));
    }

    // Helper: Get Harian Data
    private function getHarian($userId)
    {
        return Absensi::where('user_id', $userId)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->orderBy('tanggal', 'desc')
            ->get();
    }

    // Helper: Get Mingguan Data
    private function getMingguan($userId)
    {
        return Absensi::where('user_id', $userId)
            ->whereBetween('tanggal', [
                Carbon::now()->subWeeks(4)->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->orderBy('tanggal', 'desc')
            ->get();
    }

    // Helper: Get Bulanan Data
    private function getBulanan($userId)
    {
        return Absensi::where('user_id', $userId)
            ->whereBetween('tanggal', [
                Carbon::now()->subMonths(5)->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->orderBy('tanggal', 'desc')
            ->get();
    }
}