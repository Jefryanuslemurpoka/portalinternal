<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Helpers\AttendanceHelper;

class RekapController extends Controller
{
    // Tampilkan halaman rekap
    public function index(Request $request)
    {
        $user = Auth::user();
        $periode = $request->get('periode', 'bulanan'); // harian, mingguan, bulanan
        
        // Jam kerja dari Setting
        $jamMasuk = Setting::get('jam_masuk', '08:00');

        // Get periode kerja saat ini (15 - 14)
        $currentPeriod = AttendanceHelper::getCurrentPeriod();

        // Get data based on periode
        if ($periode === 'harian') {
            // Harian: periode kerja saat ini (15 - 14)
            $absensi = $this->getHarian($user->id);
            $startDate = Carbon::parse($currentPeriod['start']);
            $endDate = Carbon::parse($currentPeriod['end']);
        } elseif ($periode === 'mingguan') {
            // Mingguan: 4 minggu terakhir
            $absensi = $this->getMingguan($user->id);
            $startDate = Carbon::now()->subWeeks(4)->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
        } else {
            // Bulanan: 6 bulan terakhir
            $absensi = $this->getBulanan($user->id);
            $startDate = Carbon::now()->subMonths(5)->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        }

        // Statistik - EXCLUDE MINGGU dan IZIN/CUTI yang APPROVED
        $totalHariKerja = AttendanceHelper::countWorkingDays(
            $user->id, 
            $startDate->format('Y-m-d'), 
            $endDate->format('Y-m-d')
        );

        // Filter absensi yang valid (exclude minggu dan izin)
        $absensiValid = $absensi->filter(function($item) use ($user) {
            return AttendanceHelper::isWorkingDay($user->id, $item->tanggal);
        });

        $totalHadir = $absensiValid->count();
        $tepatWaktu = $absensiValid->filter(function($item) {
            return !AttendanceHelper::isLate($item->jam_masuk, $item->tanggal);
        })->count();
        $terlambat = $absensiValid->filter(function($item) {
            return AttendanceHelper::isLate($item->jam_masuk, $item->tanggal);
        })->count();

        // Hitung tidak hadir (hari kerja - hadir)
        $tidakHadir = $totalHariKerja - $totalHadir;

        return view('karyawan.rekap.index', compact(
            'absensi',
            'periode',
            'totalHariKerja',
            'totalHadir',
            'tepatWaktu',
            'terlambat',
            'tidakHadir',
            'jamMasuk',
            'startDate',
            'endDate'
        ));
    }

    // Rekap Harian (periode kerja saat ini: 15 - 14)
    public function harian(Request $request)
    {
        $user = Auth::user();
        $jamMasuk = Setting::get('jam_masuk', '08:00');

        $currentPeriod = AttendanceHelper::getCurrentPeriod();
        $absensi = $this->getHarian($user->id);

        // Filter absensi yang valid
        $absensiValid = $absensi->filter(function($item) use ($user) {
            return AttendanceHelper::isWorkingDay($user->id, $item->tanggal);
        });

        $totalHariKerja = AttendanceHelper::countWorkingDays(
            $user->id,
            $currentPeriod['start'],
            $currentPeriod['end']
        );

        $totalHadir = $absensiValid->count();
        $tepatWaktu = $absensiValid->filter(function($item) {
            return !AttendanceHelper::isLate($item->jam_masuk, $item->tanggal);
        })->count();
        $terlambat = $absensiValid->filter(function($item) {
            return AttendanceHelper::isLate($item->jam_masuk, $item->tanggal);
        })->count();
        $tidakHadir = $totalHariKerja - $totalHadir;

        return view('karyawan.rekap.harian', compact(
            'absensi',
            'totalHariKerja',
            'totalHadir',
            'tepatWaktu',
            'terlambat',
            'tidakHadir',
            'jamMasuk',
            'currentPeriod'
        ));
    }

    // Rekap Mingguan (4 minggu terakhir)
    public function mingguan(Request $request)
    {
        $user = Auth::user();
        $jamMasuk = Setting::get('jam_masuk', '08:00');

        $absensi = $this->getMingguan($user->id);

        $startDate = Carbon::now()->subWeeks(4)->startOfWeek();
        $endDate = Carbon::now()->endOfWeek();

        $absensiValid = $absensi->filter(function($item) use ($user) {
            return AttendanceHelper::isWorkingDay($user->id, $item->tanggal);
        });

        $totalHariKerja = AttendanceHelper::countWorkingDays(
            $user->id,
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d')
        );

        $totalHadir = $absensiValid->count();
        $tepatWaktu = $absensiValid->filter(function($item) {
            return !AttendanceHelper::isLate($item->jam_masuk, $item->tanggal);
        })->count();
        $terlambat = $absensiValid->filter(function($item) {
            return AttendanceHelper::isLate($item->jam_masuk, $item->tanggal);
        })->count();
        $tidakHadir = $totalHariKerja - $totalHadir;

        return view('karyawan.rekap.mingguan', compact(
            'absensi',
            'totalHariKerja',
            'totalHadir',
            'tepatWaktu',
            'terlambat',
            'tidakHadir',
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
            
            // Hitung periode 15-14 untuk bulan ini
            $monthPeriod = AttendanceHelper::getCurrentPeriod($date->day(20)); // Use day 20 to ensure correct period
            
            $absensi = Absensi::where('user_id', $user->id)
                ->whereBetween('tanggal', [$monthPeriod['start'], $monthPeriod['end']])
                ->get();

            $absensiValid = $absensi->filter(function($item) use ($user) {
                return AttendanceHelper::isWorkingDay($user->id, $item->tanggal);
            });

            $totalHariKerja = AttendanceHelper::countWorkingDays(
                $user->id,
                $monthPeriod['start'],
                $monthPeriod['end']
            );

            $totalHadir = $absensiValid->count();
            $tepatWaktu = $absensiValid->filter(function($item) {
                return !AttendanceHelper::isLate($item->jam_masuk, $item->tanggal);
            })->count();
            $terlambat = $absensiValid->filter(function($item) {
                return AttendanceHelper::isLate($item->jam_masuk, $item->tanggal);
            })->count();

            $rekapBulanan[] = [
                'bulan' => $date->format('F Y'),
                'bulan_short' => $date->format('M Y'),
                'periode' => $monthPeriod['start_formatted'] . ' - ' . $monthPeriod['end_formatted'],
                'total_hari_kerja' => $totalHariKerja,
                'total_hadir' => $totalHadir,
                'tepat_waktu' => $tepatWaktu,
                'terlambat' => $terlambat,
                'tidak_hadir' => $totalHariKerja - $totalHadir,
            ];
        }

        return view('karyawan.rekap.bulanan', compact('rekapBulanan'));
    }

    // Helper: Get Harian Data (Periode 15-14)
    private function getHarian($userId)
    {
        $currentPeriod = AttendanceHelper::getCurrentPeriod();
        
        return Absensi::where('user_id', $userId)
            ->whereBetween('tanggal', [$currentPeriod['start'], $currentPeriod['end']])
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