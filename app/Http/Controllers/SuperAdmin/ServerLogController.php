<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServerLog;
use App\Models\LogAktivitas;
use Carbon\Carbon;

class ServerLogController extends Controller
{
    // Tampilkan daftar log server
    public function index(Request $request)
    {
        $query = ServerLog::query();

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
        } elseif ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $serverLog = $query->orderBy('tanggal', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->paginate(15);

        // Statistik
        $totalLog = ServerLog::count();
        $statusNormal = ServerLog::where('status', 'normal')->count();
        $statusWarning = ServerLog::where('status', 'warning')->count();
        $statusError = ServerLog::where('status', 'error')->count();

        return view('superadmin.server.index', compact(
            'serverLog',
            'totalLog',
            'statusNormal',
            'statusWarning',
            'statusError'
        ));
    }

    // Tampilkan form tambah log server
    public function create()
    {
        return view('superadmin.server.create');
    }

    // Simpan log server baru
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'aktivitas' => 'required|string|max:255',
            'status' => 'required|in:normal,warning,error',
            'deskripsi' => 'nullable|string',
        ], [
            'tanggal.required' => 'Tanggal harus diisi',
            'aktivitas.required' => 'Aktivitas harus diisi',
            'status.required' => 'Status harus dipilih',
        ]);

        $serverLog = ServerLog::create([
            'tanggal' => $request->tanggal,
            'aktivitas' => $request->aktivitas,
            'status' => $request->status,
            'deskripsi' => $request->deskripsi,
        ]);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Tambah Server Log',
            'deskripsi' => 'Menambahkan log server: ' . $serverLog->aktivitas,
        ]);

        return redirect()->route('superadmin.serverlog.index')
            ->with('success', 'Log server berhasil ditambahkan');
    }

    // Tampilkan form edit log server
    public function edit($id)
    {
        $serverLog = ServerLog::findOrFail($id);
        return view('superadmin.server.edit', compact('serverLog'));
    }

    // Update log server
    public function update(Request $request, $id)
    {
        $serverLog = ServerLog::findOrFail($id);

        $request->validate([
            'tanggal' => 'required|date',
            'aktivitas' => 'required|string|max:255',
            'status' => 'required|in:normal,warning,error',
            'deskripsi' => 'nullable|string',
        ], [
            'tanggal.required' => 'Tanggal harus diisi',
            'aktivitas.required' => 'Aktivitas harus diisi',
            'status.required' => 'Status harus dipilih',
        ]);

        $serverLog->update([
            'tanggal' => $request->tanggal,
            'aktivitas' => $request->aktivitas,
            'status' => $request->status,
            'deskripsi' => $request->deskripsi,
        ]);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Update Server Log',
            'deskripsi' => 'Mengupdate log server: ' . $serverLog->aktivitas,
        ]);

        return redirect()->route('superadmin.serverlog.index')
            ->with('success', 'Log server berhasil diperbarui');
    }

    // Hapus log server
    public function destroy($id)
    {
        $serverLog = ServerLog::findOrFail($id);
        $aktivitas = $serverLog->aktivitas;

        $serverLog->delete();

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Hapus Server Log',
            'deskripsi' => 'Menghapus log server: ' . $aktivitas,
        ]);

        return redirect()->route('superadmin.serverlog.index')
            ->with('success', 'Log server berhasil dihapus');
    }
}