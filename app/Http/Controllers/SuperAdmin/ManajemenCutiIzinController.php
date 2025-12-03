<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CutiIzin;
use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ManajemenCutiIzinController extends Controller
{
    /**
     * Tampilkan semua pengajuan cuti/izin untuk SuperAdmin
     */
    public function index(Request $request)
    {
        $query = CutiIzin::with('user');

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan jenis
        if ($request->has('jenis') && $request->jenis != '') {
            $query->where('jenis', $request->jenis);
        }

        // Search berdasarkan nama karyawan
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $cutiIzin = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('superadmin.cutiizin.index', compact('cutiIzin'));
    }

    /**
     * Tampilkan detail pengajuan
     */
    public function show($id)
    {
        $cutiIzin = CutiIzin::with('user')->findOrFail($id);
        return view('superadmin.cutiizin.show', compact('cutiIzin'));
    }

    /**
     * Setujui pengajuan cuti/izin
     */
    public function approve($id)
    {
        $cutiIzin = CutiIzin::findOrFail($id);

        if ($cutiIzin->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan ini sudah diproses sebelumnya');
        }

        $cutiIzin->update([
            'status' => 'disetujui',
            'approved_by' => Auth::id(),
            'tanggal_persetujuan' => now(),
            'catatan_approval' => request('catatan')
        ]);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menyetujui ' . ucfirst($cutiIzin->jenis),
            'deskripsi' => 'Menyetujui pengajuan ' . $cutiIzin->jenis . ' dari ' . $cutiIzin->user->name,
        ]);

        return redirect()->route('superadmin.cutiizin.index')
            ->with('success', 'Pengajuan ' . $cutiIzin->jenis . ' berhasil disetujui');
    }

    /**
     * Tolak pengajuan cuti/izin
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500'
        ]);

        $cutiIzin = CutiIzin::findOrFail($id);

        if ($cutiIzin->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan ini sudah diproses sebelumnya');
        }

        $cutiIzin->update([
            'status' => 'ditolak',
            'approved_by' => Auth::id(),
            'tanggal_persetujuan' => now(),
            'catatan_approval' => $request->alasan_penolakan
        ]);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menolak ' . ucfirst($cutiIzin->jenis),
            'deskripsi' => 'Menolak pengajuan ' . $cutiIzin->jenis . ' dari ' . $cutiIzin->user->name,
        ]);

        return redirect()->route('superadmin.cutiizin.index')
            ->with('success', 'Pengajuan ' . $cutiIzin->jenis . ' berhasil ditolak');
    }

    /**
     * Hapus pengajuan (force delete untuk SuperAdmin)
     */
    public function destroy($id)
    {
        $cutiIzin = CutiIzin::findOrFail($id);
        $jenis = $cutiIzin->jenis;
        $userName = $cutiIzin->user->name;

        // Hapus file jika ada
        if ($cutiIzin->file_pendukung && Storage::disk('public')->exists($cutiIzin->file_pendukung)) {
            Storage::disk('public')->delete($cutiIzin->file_pendukung);
        }

        $cutiIzin->delete();

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Hapus Pengajuan ' . ucfirst($jenis),
            'deskripsi' => 'Menghapus pengajuan ' . $jenis . ' dari ' . $userName,
        ]);

        return redirect()->route('superadmin.cutiizin.index')
            ->with('success', "Pengajuan {$jenis} berhasil dihapus");
    }

    /**
     * Download file pendukung
     */
    public function downloadFile($id)
    {
        $cutiIzin = CutiIzin::findOrFail($id);

        if (!$cutiIzin->file_pendukung) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        $filePath = storage_path('app/public/' . $cutiIzin->file_pendukung);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server');
        }

        return response()->download($filePath);
    }

    /**
     * Laporan Cuti/Izin
     */
    public function laporan(Request $request)
    {
        $query = CutiIzin::with('user');

        // Filter tanggal
        if ($request->has('tanggal_mulai') && $request->tanggal_mulai != '') {
            $query->where('tanggal_mulai', '>=', $request->tanggal_mulai);
        }

        if ($request->has('tanggal_selesai') && $request->tanggal_selesai != '') {
            $query->where('tanggal_selesai', '<=', $request->tanggal_selesai);
        }

        // Filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter jenis
        if ($request->has('jenis') && $request->jenis != '') {
            $query->where('jenis', $request->jenis);
        }

        $data = $query->orderBy('tanggal_mulai', 'desc')->get();

        // Statistik
        $statistik = [
            'total' => $data->count(),
            'pending' => $data->where('status', 'pending')->count(),
            'disetujui' => $data->where('status', 'disetujui')->count(),
            'ditolak' => $data->where('status', 'ditolak')->count(),
            'total_cuti' => $data->where('jenis', 'cuti')->count(),
            'total_izin' => $data->where('jenis', 'izin')->count(),
        ];

        return view('superadmin.cutiizin.laporan', compact('data', 'statistik'));
    }
}