<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    // Tampilkan rekap absensi
    public function index(Request $request)
    {
        $query = Absensi::with('user');

        // Filter tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
        } elseif ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        } else {
            // Default: hari ini
            $query->whereDate('tanggal', Carbon::today());
        }

        // Filter divisi
        if ($request->filled('divisi')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('divisi', $request->divisi);
            });
        }

        // Filter karyawan
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $absensi = $query->orderBy('tanggal', 'desc')
                        ->orderBy('jam_masuk', 'desc')
                        ->paginate(15);

        // Data untuk filter
        $karyawan = User::where('role', 'karyawan')
                       ->where('status', 'aktif')
                       ->orderBy('name')
                       ->get();

        $divisiList = User::where('role', 'karyawan')
                         ->select('divisi')
                         ->distinct()
                         ->orderBy('divisi')
                         ->pluck('divisi');

        // Statistik
        $totalHadir = $absensi->total();
        $tepatWaktu = $absensi->where('jam_masuk', '<=', '08:00:00')->count();
        $terlambat = $absensi->where('jam_masuk', '>', '08:00:00')->count();

        return view('superadmin.absensi.index', compact(
            'absensi',
            'karyawan',
            'divisiList',
            'totalHadir',
            'tepatWaktu',
            'terlambat'
        ));
    }

    // Filter absensi (AJAX)
    public function filter(Request $request)
    {
        // Method ini bisa digunakan untuk AJAX request jika diperlukan
        return $this->index($request);
    }

    // Detail absensi
    public function show($id)
    {
        $absensi = Absensi::with('user')->findOrFail($id);
        return view('superadmin.absensi.show', compact('absensi'));
    }

    // Hapus absensi
    public function destroy($id)
    {
        $absensi = Absensi::findOrFail($id);
        $namaKaryawan = $absensi->user->name;
        $tanggal = $absensi->tanggal->format('d M Y');

        $absensi->delete();

        return redirect()->route('superadmin.absensi.index')
            ->with('success', "Absensi {$namaKaryawan} tanggal {$tanggal} berhasil dihapus");
    }
}