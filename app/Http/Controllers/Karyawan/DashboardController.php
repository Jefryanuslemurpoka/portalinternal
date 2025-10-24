<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\CutiIzin;
use App\Models\Pengumuman;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Status Kehadiran Hari Ini
        $absensiHariIni = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        $sudahCheckIn = $absensiHariIni ? true : false;
        $sudahCheckOut = $absensiHariIni && $absensiHariIni->jam_keluar ? true : false;

        // Jam Kerja dari Cache
        $jamMasuk = Cache::get('jam_masuk', '08:00');
        $jamKeluar = Cache::get('jam_keluar', '17:00');

        // Statistik Bulan Ini
        $absensiCount = Absensi::where('user_id', $user->id)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->count();

        $tepatWaktu = Absensi::where('user_id', $user->id)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->where('jam_masuk', '<=', $jamMasuk)
            ->count();

        $terlambat = Absensi::where('user_id', $user->id)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->where('jam_masuk', '>', $jamMasuk)
            ->count();

        // Data Grafik Absensi 7 Hari Terakhir
        $labels = [];
        $dataAbsensi = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d M');
            
            $absen = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', $date)
                ->exists();
            
            $dataAbsensi[] = $absen ? 1 : 0;
        }

        // Pengajuan Cuti/Izin
        $cutiPending = CutiIzin::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $cutiDisetujui = CutiIzin::where('user_id', $user->id)
            ->where('status', 'disetujui')
            ->whereMonth('tanggal_mulai', Carbon::now()->month)
            ->count();

        // Pengumuman Terbaru (3 terakhir)
        $pengumuman = Pengumuman::with('creator')
            ->orderBy('tanggal', 'desc')
            ->limit(3)
            ->get();

        // Riwayat Absensi Terbaru (5 terakhir)
        $riwayatAbsensi = Absensi::where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        return view('karyawan.dashboard', compact(
            'absensiHariIni',
            'sudahCheckIn',
            'sudahCheckOut',
            'jamMasuk',
            'jamKeluar',
            'absensiCount',
            'tepatWaktu',
            'terlambat',
            'labels',
            'dataAbsensi',
            'cutiPending',
            'cutiDisetujui',
            'pengumuman',
            'riwayatAbsensi'
        ));
    }
}