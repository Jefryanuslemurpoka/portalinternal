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

        // Statistik
        $totalHadir = $absensi->count();
        $tepatWaktu = $absensi->where('jam_masuk', '<=', '08:00:00')->count();
        $terlambat = $absensi->where('jam_masuk', '>', '08:00:00')->count();

        $data = [
            'absensi' => $absensi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'divisi' => $request->divisi,
            'totalHadir' => $totalHadir,
            'tepatWaktu' => $tepatWaktu,
            'terlambat' => $terlambat,
        ];

        return view('superadmin.laporan.absensi', $data);
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

        // Statistik
        $totalPengajuan = $cutiIzin->count();
        $pending = $cutiIzin->where('status', 'pending')->count();
        $disetujui = $cutiIzin->where('status', 'disetujui')->count();
        $ditolak = $cutiIzin->where('status', 'ditolak')->count();

        $data = [
            'cutiIzin' => $cutiIzin,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status,
            'totalPengajuan' => $totalPengajuan,
            'pending' => $pending,
            'disetujui' => $disetujui,
            'ditolak' => $ditolak,
        ];

        return view('superadmin.laporan.cutiizin', $data);
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

        // Statistik
        $totalKaryawan = $karyawan->count();
        $aktif = $karyawan->where('status', 'aktif')->count();
        $nonaktif = $karyawan->where('status', 'nonaktif')->count();

        $data = [
            'karyawan' => $karyawan,
            'divisi' => $request->divisi,
            'status' => $request->status,
            'totalKaryawan' => $totalKaryawan,
            'aktif' => $aktif,
            'nonaktif' => $nonaktif,
        ];

        return view('superadmin.laporan.karyawan', $data);
    }

    // Export Laporan
    public function export(Request $request)
    {
        $jenis = $request->input('jenis'); // absensi, cutiizin, karyawan
        $format = $request->input('format'); // pdf, excel

        if ($jenis === 'absensi') {
            return $this->exportAbsensi($request, $format);
        } elseif ($jenis === 'cutiizin') {
            return $this->exportCutiIzin($request, $format);
        } elseif ($jenis === 'karyawan') {
            return $this->exportKaryawan($request, $format);
        }

        return redirect()->back()->with('error', 'Jenis laporan tidak valid');
    }

    // Export Absensi
    private function exportAbsensi($request, $format)
    {
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
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'divisi' => $request->divisi,
        ];

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('superadmin.laporan.pdf.absensi', $data);
            return $pdf->download('laporan-absensi-' . date('Ymd') . '.pdf');
        } else {
            // Excel export using simple CSV
            return $this->exportAbsensiExcel($absensi);
        }
    }

    // Export Cuti/Izin
    private function exportCutiIzin($request, $format)
    {
        $query = CutiIzin::with('user', 'approver')
            ->whereBetween('tanggal_mulai', [$request->tanggal_mulai, $request->tanggal_selesai]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $cutiIzin = $query->orderBy('tanggal_mulai', 'desc')->get();

        $data = [
            'cutiIzin' => $cutiIzin,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status,
        ];

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('superadmin.laporan.pdf.cutiizin', $data);
            return $pdf->download('laporan-cutiizin-' . date('Ymd') . '.pdf');
        } else {
            return $this->exportCutiIzinExcel($cutiIzin);
        }
    }

    // Export Karyawan
    private function exportKaryawan($request, $format)
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

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('superadmin.laporan.pdf.karyawan', $data);
            return $pdf->download('laporan-karyawan-' . date('Ymd') . '.pdf');
        } else {
            return $this->exportKaryawanExcel($karyawan);
        }
    }

    // Export to Excel (CSV)
    private function exportAbsensiExcel($absensi)
    {
        $filename = 'laporan-absensi-' . date('Ymd') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($absensi) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Tanggal', 'Nama', 'Divisi', 'Jam Masuk', 'Jam Keluar', 'Lokasi']);

            foreach ($absensi as $key => $a) {
                fputcsv($file, [
                    $key + 1,
                    $a->tanggal->format('d/m/Y'),
                    $a->user->name,
                    $a->user->divisi,
                    $a->jam_masuk ? date('H:i', strtotime($a->jam_masuk)) : '-',
                    $a->jam_keluar ? date('H:i', strtotime($a->jam_keluar)) : '-',
                    $a->lokasi ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportCutiIzinExcel($cutiIzin)
    {
        $filename = 'laporan-cutiizin-' . date('Ymd') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($cutiIzin) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Nama', 'Jenis', 'Tanggal Mulai', 'Tanggal Selesai', 'Alasan', 'Status', 'Approver']);

            foreach ($cutiIzin as $key => $ci) {
                fputcsv($file, [
                    $key + 1,
                    $ci->user->name,
                    ucfirst($ci->jenis),
                    $ci->tanggal_mulai->format('d/m/Y'),
                    $ci->tanggal_selesai->format('d/m/Y'),
                    $ci->alasan,
                    ucfirst($ci->status),
                    $ci->approver ? $ci->approver->name : '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportKaryawanExcel($karyawan)
    {
        $filename = 'laporan-karyawan-' . date('Ymd') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($karyawan) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Nama', 'Email', 'Divisi', 'Status', 'Terdaftar']);

            foreach ($karyawan as $key => $k) {
                fputcsv($file, [
                    $key + 1,
                    $k->name,
                    $k->email,
                    $k->divisi,
                    ucfirst($k->status),
                    $k->created_at->format('d/m/Y'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}