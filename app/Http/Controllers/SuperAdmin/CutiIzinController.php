<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CutiIzin;
use App\Models\LogAktivitas;

class CutiIzinController extends Controller
{
    // Tampilkan daftar pengajuan cuti/izin
    public function index()
    {
        $cutiIzin = CutiIzin::with('user', 'approver')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('superadmin.cutiizin.index', compact('cutiIzin'));
    }

    // Tampilkan detail pengajuan
    public function show($id)
    {
        $cutiIzin = CutiIzin::with('user', 'approver')->findOrFail($id);
        return view('superadmin.cutiizin.show', compact('cutiIzin'));
    }

    // Setujui pengajuan
    public function approve(Request $request, $id)
    {
        $cutiIzin = CutiIzin::findOrFail($id);

        $request->validate([
            'keterangan_approval' => 'nullable|string|max:500',
        ]);

        $cutiIzin->update([
            'status' => 'disetujui',
            'approved_by' => auth()->id(),
            'keterangan_approval' => $request->keterangan_approval,
        ]);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Approve Cuti/Izin',
            'deskripsi' => 'Menyetujui pengajuan ' . $cutiIzin->jenis . ' dari ' . $cutiIzin->user->name,
        ]);

        return redirect()->route('superadmin.cutiizin.index')
            ->with('success', 'Pengajuan berhasil disetujui');
    }

    // Tolak pengajuan
    public function reject(Request $request, $id)
    {
        $cutiIzin = CutiIzin::findOrFail($id);

        $request->validate([
            'keterangan_approval' => 'required|string|max:500',
        ], [
            'keterangan_approval.required' => 'Alasan penolakan harus diisi',
        ]);

        $cutiIzin->update([
            'status' => 'ditolak',
            'approved_by' => auth()->id(),
            'keterangan_approval' => $request->keterangan_approval,
        ]);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Reject Cuti/Izin',
            'deskripsi' => 'Menolak pengajuan ' . $cutiIzin->jenis . ' dari ' . $cutiIzin->user->name,
        ]);

        return redirect()->route('superadmin.cutiizin.index')
            ->with('success', 'Pengajuan berhasil ditolak');
    }
}