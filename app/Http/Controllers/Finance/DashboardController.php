<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\CutiIzin;
use App\Models\Pengumuman;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // ========================
        // STATUS KEHADIRAN HARI INI
        // ========================
        
        $absensiHariIni = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        $sudahCheckIn = $absensiHariIni ? true : false;
        $sudahCheckOut = $absensiHariIni && $absensiHariIni->jam_keluar ? true : false;

        // Ambil Jam Kerja dari Model Setting
        $jamMasuk = Setting::get('jam_masuk', '08:00');
        $jamKeluar = Setting::get('jam_keluar', '17:00');
        $toleransi = (int) Setting::get('toleransi_keterlambatan', 15);

        // Hitung batas keterlambatan (jam_masuk + toleransi)
        $batasKeterlambatan = Carbon::createFromFormat('H:i', $jamMasuk)
            ->addMinutes($toleransi)
            ->format('H:i:s');

        // ========================
        // STATISTIK ABSENSI BULAN INI
        // ========================
        
        $absensiCount = Absensi::where('user_id', $user->id)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->count();

        // Tepat Waktu = jam_masuk <= batas keterlambatan
        $tepatWaktu = Absensi::where('user_id', $user->id)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->whereRaw('TIME(jam_masuk) <= ?', [$batasKeterlambatan])
            ->count();

        // Terlambat = jam_masuk > batas keterlambatan
        $terlambat = Absensi::where('user_id', $user->id)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->whereRaw('TIME(jam_masuk) > ?', [$batasKeterlambatan])
            ->count();

        // ========================
        // DATA GRAFIK ABSENSI 7 HARI TERAKHIR
        // ========================
        
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

        // ========================
        // PENGAJUAN CUTI/IZIN
        // ========================
        
        $cutiPending = CutiIzin::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $cutiDisetujui = CutiIzin::where('user_id', $user->id)
            ->where('status', 'disetujui')
            ->whereMonth('tanggal_mulai', Carbon::now()->month)
            ->count();

        // ========================
        // PENGUMUMAN TERBARU (3 terakhir)
        // ========================
        
        $pengumuman = Pengumuman::with('creator')
            ->orderBy('tanggal', 'desc')
            ->limit(3)
            ->get();

        // ========================
        // RIWAYAT ABSENSI TERBARU (5 terakhir)
        // ========================
        
        $riwayatAbsensi = Absensi::where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        // ========================
        // DATA KEUANGAN (TODO: Implement logic finance)
        // ========================
        
        // TODO: Tambahkan query untuk data finance
        // Contoh:
        // $totalPemasukan = Pemasukan::whereMonth('tanggal', Carbon::now()->month)->sum('jumlah');
        // $totalPengeluaran = Pengeluaran::whereMonth('tanggal', Carbon::now()->month)->sum('jumlah');
        // $saldo = $totalPemasukan - $totalPengeluaran;
        // $invoicePending = Invoice::where('status', 'pending')->count();

        return view('finance.dashboard', compact(
            'absensiHariIni',
            'sudahCheckIn',
            'sudahCheckOut',
            'jamMasuk',
            'jamKeluar',
            'toleransi',
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