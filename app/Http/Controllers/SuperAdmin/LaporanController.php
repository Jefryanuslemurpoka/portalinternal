<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use App\Models\CutiIzin;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

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

        $absensi = $query->orderBy('tanggal', 'desc')->get();

        $data = [
            'absensi' => $absensi,
            'tanggalMulai' => $request->tanggal_mulai,
            'tanggalSelesai' => $request->tanggal_selesai,
            'divisi' => $request->divisi,
        ];

        $pdf = Pdf::loadView('superadmin.laporan.pdf.absensi', $data)
            ->setPaper('a4', 'landscape');
        
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

        $absensi = $query->orderBy('tanggal', 'desc')->get();

        $filename = 'Laporan_Absensi_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($absensi) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM untuk Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, ['No', 'Tanggal', 'Nama Karyawan', 'NIK', 'Divisi', 'Jabatan', 'Status', 'Jam Masuk', 'Jam Keluar']);

            // Data
            foreach ($absensi as $key => $item) {
                fputcsv($file, [
                    $key + 1,
                    \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y'),
                    $item->user->name,
                    $item->user->nik ?? '-',
                    $item->user->divisi,
                    $item->user->jabatan,
                    strtoupper($item->status),
                    $item->jam_masuk ?? '-',
                    $item->jam_keluar ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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

        $cutiIzin = $query->orderBy('tanggal_mulai', 'desc')->get();

        $data = [
            'cutiIzin' => $cutiIzin,
            'tanggalMulai' => $request->tanggal_mulai,
            'tanggalSelesai' => $request->tanggal_selesai,
            'status' => $request->status,
        ];

        $pdf = Pdf::loadView('superadmin.laporan.pdf.cutiizin', $data)
            ->setPaper('a4', 'landscape');
        
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

        $cutiIzin = $query->orderBy('tanggal_mulai', 'desc')->get();

        $filename = 'Laporan_CutiIzin_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($cutiIzin) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, ['No', 'Nama Karyawan', 'NIK', 'Divisi', 'Jenis', 'Tanggal Mulai', 'Tanggal Selesai', 'Durasi', 'Status', 'Keterangan']);

            // Data
            foreach ($cutiIzin as $key => $item) {
                fputcsv($file, [
                    $key + 1,
                    $item->user->name,
                    $item->user->nik ?? '-',
                    $item->user->divisi,
                    strtoupper($item->jenis),
                    \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y'),
                    \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y'),
                    $item->durasi . ' hari',
                    strtoupper($item->status),
                    $item->keterangan ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
            ->setPaper('a4', 'landscape');
        
        return $pdf->download('Laporan_Karyawan_' . date('Ymd_His') . '.pdf');
    }

    // Export Excel Karyawan
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

        $filename = 'Laporan_Karyawan_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($karyawan) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, ['No', 'NIK', 'Nama', 'Email', 'Divisi', 'Jabatan', 'No. HP', 'Status', 'Terdaftar']);

            // Data
            foreach ($karyawan as $key => $item) {
                fputcsv($file, [
                    $key + 1,
                    $item->nik ?? '-',
                    $item->name,
                    $item->email,
                    $item->divisi,
                    $item->jabatan,
                    $item->no_hp ?? '-',
                    strtoupper($item->status),
                    $item->created_at->format('d/m/Y'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}