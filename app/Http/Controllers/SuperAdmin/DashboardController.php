<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use App\Models\CutiIzin;
use App\Models\Pengumuman;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung Total Karyawan
        $totalKaryawan = User::where('role', 'karyawan')->count();
        $karyawanAktif = User::where('role', 'karyawan')->where('status', 'aktif')->count();
        $karyawanNonaktif = User::where('role', 'karyawan')->where('status', 'nonaktif')->count();

        // Hitung Absensi Hari Ini
        $today = Carbon::today();
        $absensiHariIni = Absensi::whereDate('tanggal', $today)->count();
        $belumAbsen = $karyawanAktif - $absensiHariIni;

        // Hitung Pengajuan Cuti/Izin Pending
        $cutiPending = CutiIzin::where('status', 'pending')->count();

        // Hitung Total Pengumuman
        $totalPengumuman = Pengumuman::count();

        // Data Grafik Kehadiran 7 Hari Terakhir
        $labels = [];
        $dataHadir = [];
        $dataTidakHadir = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            
            // CEK HARI LIBUR (0 = Minggu aja)
            $isDayOff = $date->dayOfWeek === 0; // Cuma Minggu
            
            // Format label dengan keterangan libur
            $labels[] = $date->format('d M') . ($isDayOff ? ' (Libur)' : '');
            
            if ($isDayOff) {
                // Jika hari libur, set 0 untuk kedua data
                $dataHadir[] = 0;
                $dataTidakHadir[] = 0;
            } else {
                // Hitung yang hadir
                $hadir = Absensi::whereDate('tanggal', $date)->count();
                $dataHadir[] = $hadir;
                $dataTidakHadir[] = $karyawanAktif - $hadir;
            }
        }

        // Data Grafik Absensi per Divisi
        $divisiData = User::where('role', 'karyawan')
            ->where('status', 'aktif')
            ->select('divisi', DB::raw('count(*) as total'))
            ->groupBy('divisi')
            ->get();

        $divisiLabels = $divisiData->pluck('divisi')->toArray();
        $divisiValues = $divisiData->pluck('total')->toArray();

        // Pengajuan Cuti/Izin Terbaru
        $cutiTerbaru = CutiIzin::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Karyawan Terbaru
        $karyawanTerbaru = User::where('role', 'karyawan')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Pengumuman Terbaru
        $pengumumanTerbaru = Pengumuman::with('creator')
            ->orderBy('tanggal', 'desc')
            ->limit(3)
            ->get();

        return view('superadmin.dashboard', compact(
            'totalKaryawan',
            'karyawanAktif',
            'karyawanNonaktif',
            'absensiHariIni',
            'belumAbsen',
            'cutiPending',
            'totalPengumuman',
            'labels',
            'dataHadir',
            'dataTidakHadir',
            'divisiLabels',
            'divisiValues',
            'cutiTerbaru',
            'karyawanTerbaru',
            'pengumumanTerbaru'
        ));
    }
}