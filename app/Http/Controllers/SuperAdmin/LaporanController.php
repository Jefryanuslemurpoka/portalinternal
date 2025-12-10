<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use App\Models\CutiIzin;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiExport;
use App\Exports\CutiIzinExport;
use App\Exports\KaryawanExport; // Tambahkan ini

class LaporanController extends Controller
{
    // Tampilkan halaman laporan
    public function index()
    {
        return view('superadmin.laporan.index');
    }

    // Laporan Absensi
    public function absensi(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'divisi' => 'nullable|string',
        ]);

        $query = Absensi::with('user')
            ->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);

        if ($request->filled('divisi')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('divisi', $request->divisi);
            });
        }

        $absensi = $query->orderBy('tanggal', 'desc')->get();

        $data = [
            'absensi' => $absensi,
            'tanggalMulai' => $request->tanggal_mulai,
            'tanggalSelesai' => $request->tanggal_selesai,
            'divisi' => $request->divisi,
        ];

        return view('superadmin.laporan.absensi', $data);
    }

    // Export PDF Absensi
    public function exportAbsensiPDF(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'divisi' => 'nullable|string',
        ]);

        $query = Absensi::with('user')
            ->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);

        if ($request->filled('divisi')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('divisi', $request->divisi);
            });
        }

        $absensi = $query->orderBy('tanggal', 'asc')->get();

        $data = [
            'absensi' => $absensi,
            'tanggalMulai' => $request->tanggal_mulai,
            'tanggalSelesai' => $request->tanggal_selesai,
            'divisi' => $request->divisi,
        ];

        $pdf = Pdf::loadView('superadmin.laporan.pdf.absensi', $data)
            ->setPaper('a4', 'portrait');
        
        return $pdf->download('Laporan_Absensi_' . date('Ymd_His') . '.pdf');
    }

    // Export Excel Absensi
    public function exportAbsensiExcel(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'divisi' => 'nullable|string',
        ]);

        $query = Absensi::with('user')
            ->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);

        if ($request->filled('divisi')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('divisi', $request->divisi);
            });
        }

        $absensi = $query->orderBy('tanggal', 'asc')->get();

        return Excel::download(
            new AbsensiExport($absensi, $request->tanggal_mulai, $request->tanggal_selesai, $request->divisi),
            'Laporan_Absensi_' . date('Ymd_His') . '.xlsx'
        );
    }

    // Laporan Cuti/Izin
    public function cutiIzin(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'nullable|in:pending,disetujui,ditolak',
        ]);

        $query = CutiIzin::with('user', 'approver')
            ->whereBetween('tanggal_mulai', [$request->tanggal_mulai, $request->tanggal_selesai]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $cutiIzin = $query->orderBy('tanggal_mulai', 'desc')->get();

        $data = [
            'cutiIzin' => $cutiIzin,
            'tanggalMulai' => $request->tanggal_mulai,
            'tanggalSelesai' => $request->tanggal_selesai,
            'status' => $request->status,
        ];

        return view('superadmin.laporan.cutiizin', $data);
    }

    // Export PDF Cuti/Izin
    public function exportCutiIzinPDF(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'status' => 'nullable|in:pending,disetujui,ditolak',
        ]);

        $query = CutiIzin::with('user', 'approver')
            ->whereBetween('tanggal_mulai', [$request->tanggal_mulai, $request->tanggal_selesai]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $cutiIzin = $query->orderBy('tanggal_mulai', 'asc')->get();

        $data = [
            'cutiIzin' => $cutiIzin,
            'tanggalMulai' => $request->tanggal_mulai,
            'tanggalSelesai' => $request->tanggal_selesai,
            'status' => $request->status,
        ];

        $pdf = Pdf::loadView('superadmin.laporan.pdf.cutiizin', $data)
            ->setPaper('a4', 'portrait');
        
        return $pdf->download('Laporan_CutiIzin_' . date('Ymd_His') . '.pdf');
    }

    // Export Excel Cuti/Izin
    public function exportCutiIzinExcel(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'status' => 'nullable|in:pending,disetujui,ditolak',
        ]);

        $query = CutiIzin::with('user', 'approver')
            ->whereBetween('tanggal_mulai', [$request->tanggal_mulai, $request->tanggal_selesai]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $cutiIzin = $query->orderBy('tanggal_mulai', 'asc')->get();

        return Excel::download(
            new CutiIzinExport($cutiIzin, $request->tanggal_mulai, $request->tanggal_selesai, $request->status),
            'Laporan_CutiIzin_' . date('Ymd_His') . '.xlsx'
        );
    }

    // Laporan Karyawan
    public function karyawan(Request $request)
    {
        $query = User::where('role', 'karyawan');

        if ($request->filled('divisi')) {
            $query->where('divisi', $request->divisi);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $karyawan = $query->orderBy('name')->get();

        $data = [
            'karyawan' => $karyawan,
            'divisi' => $request->divisi,
            'status' => $request->status,
        ];

        return view('superadmin.laporan.karyawan', $data);
    }

    // Export PDF Karyawan
    public function exportKaryawanPDF(Request $request)
    {
        $query = User::where('role', 'karyawan');

        if ($request->filled('divisi')) {
            $query->where('divisi', $request->divisi);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $karyawan = $query->orderBy('name')->get();

        $data = [
            'karyawan' => $karyawan,
            'divisi' => $request->divisi,
            'status' => $request->status,
        ];

        $pdf = Pdf::loadView('superadmin.laporan.pdf.karyawan', $data)
            ->setPaper('a4', 'portrait'); // Ubah ke portrait
        
        return $pdf->download('Laporan_Karyawan_' . date('Ymd_His') . '.pdf');
    }

    // Export Excel Karyawan - UPDATED
    public function exportKaryawanExcel(Request $request)
    {
        $query = User::where('role', 'karyawan');

        if ($request->filled('divisi')) {
            $query->where('divisi', $request->divisi);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $karyawan = $query->orderBy('name')->get();

        return Excel::download(
            new KaryawanExport($karyawan, $request->divisi, $request->status),
            'Laporan_Karyawan_' . date('Ymd_His') . '.xlsx'
        );
    }
}