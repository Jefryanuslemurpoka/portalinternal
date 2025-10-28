<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CutiIzin;
use App\Models\User;
use App\Models\LogAktivitas;

class CutiIzinController extends Controller
{
    // Tampilkan daftar pengajuan cuti/izin
    public function index(Request $request)
    {
        $query = CutiIzin::with(['user', 'approver']);

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter karyawan
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal_mulai', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        $cutiIzin = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('superadmin.cutiizin.index', compact('cutiIzin'));
    }

    // Tampilkan form create
    public function create()
    {
        $karyawan = User::where('role', 'karyawan')
                       ->where('status', 'aktif')
                       ->orderBy('name')
                       ->get();

        return view('superadmin.cutiizin.create', compact('karyawan'));
    }

    // Simpan pengajuan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'jenis' => 'required|in:cuti,izin,sakit',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:1000',
            'dokumen' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'user_id.required' => 'Karyawan harus dipilih',
            'user_id.exists' => 'Karyawan tidak valid',
            'jenis.required' => 'Jenis harus dipilih',
            'jenis.in' => 'Jenis tidak valid',
            'tanggal_mulai.required' => 'Tanggal mulai harus diisi',
            'tanggal_mulai.date' => 'Format tanggal mulai tidak valid',
            'tanggal_selesai.required' => 'Tanggal selesai harus diisi',
            'tanggal_selesai.date' => 'Format tanggal selesai tidak valid',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai',
            'alasan.required' => 'Alasan harus diisi',
            'alasan.max' => 'Alasan maksimal 1000 karakter',
            'dokumen.file' => 'Dokumen harus berupa file',
            'dokumen.mimes' => 'Dokumen harus berformat PDF, JPG, JPEG, atau PNG',
            'dokumen.max' => 'Ukuran dokumen maksimal 2MB',
        ]);

        // Upload dokumen jika ada
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $filename = 'cuti_izin_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('cuti-izin', $filename, 'public');
            $validated['dokumen'] = $path;
        }

        // Set status default
        $validated['status'] = 'pending';

        // Simpan data
        $cutiIzin = CutiIzin::create($validated);

        $user = User::find($validated['user_id']);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Tambah Cuti/Izin',
            'deskripsi' => 'Menambahkan pengajuan ' . $validated['jenis'] . ' untuk ' . $user->name,
        ]);

        return redirect()->route('superadmin.cutiizin.index')
            ->with('success', "Pengajuan {$validated['jenis']} untuk {$user->name} berhasil ditambahkan");
    }

    // Tampilkan detail
    public function show($id)
    {
        $cutiIzin = CutiIzin::with(['user', 'approver'])->findOrFail($id);
        return view('superadmin.cutiizin.show', compact('cutiIzin'));
    }

    // Tampilkan form edit
    public function edit($id)
    {
        $cutiIzin = CutiIzin::with('user')->findOrFail($id);
        
        $karyawan = User::where('role', 'karyawan')
                       ->where('status', 'aktif')
                       ->orderBy('name')
                       ->get();

        return view('superadmin.cutiizin.edit', compact('cutiIzin', 'karyawan'));
    }

    // Update data
    public function update(Request $request, $id)
    {
        $cutiIzin = CutiIzin::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'jenis' => 'required|in:cuti,izin,sakit',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:1000',
            'dokumen' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'user_id.required' => 'Karyawan harus dipilih',
            'user_id.exists' => 'Karyawan tidak valid',
            'jenis.required' => 'Jenis harus dipilih',
            'jenis.in' => 'Jenis tidak valid',
            'tanggal_mulai.required' => 'Tanggal mulai harus diisi',
            'tanggal_mulai.date' => 'Format tanggal mulai tidak valid',
            'tanggal_selesai.required' => 'Tanggal selesai harus diisi',
            'tanggal_selesai.date' => 'Format tanggal selesai tidak valid',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai',
            'alasan.required' => 'Alasan harus diisi',
            'alasan.max' => 'Alasan maksimal 1000 karakter',
            'dokumen.file' => 'Dokumen harus berupa file',
            'dokumen.mimes' => 'Dokumen harus berformat PDF, JPG, JPEG, atau PNG',
            'dokumen.max' => 'Ukuran dokumen maksimal 2MB',
        ]);

        // Hapus dokumen lama jika checkbox hapus dicentang
        if ($request->has('hapus_dokumen')) {
            if ($cutiIzin->dokumen && \Storage::disk('public')->exists($cutiIzin->dokumen)) {
                \Storage::disk('public')->delete($cutiIzin->dokumen);
            }
            $validated['dokumen'] = null;
        }

        // Upload dokumen baru jika ada
        if ($request->hasFile('dokumen')) {
            // Hapus dokumen lama
            if ($cutiIzin->dokumen && \Storage::disk('public')->exists($cutiIzin->dokumen)) {
                \Storage::disk('public')->delete($cutiIzin->dokumen);
            }

            $file = $request->file('dokumen');
            $filename = 'cuti_izin_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('cuti-izin', $filename, 'public');
            $validated['dokumen'] = $path;
        }

        // Update data
        $cutiIzin->update($validated);

        $user = User::find($validated['user_id']);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Update Cuti/Izin',
            'deskripsi' => 'Memperbarui pengajuan ' . $validated['jenis'] . ' untuk ' . $user->name,
        ]);

        return redirect()->route('superadmin.cutiizin.index')
            ->with('success', "Pengajuan {$validated['jenis']} untuk {$user->name} berhasil diperbarui");
    }

    // Hapus data
    public function destroy($id)
    {
        $cutiIzin = CutiIzin::findOrFail($id);
        
        $namaKaryawan = $cutiIzin->user->name;
        $jenis = $cutiIzin->jenis;

        // Hapus dokumen jika ada
        if ($cutiIzin->dokumen && \Storage::disk('public')->exists($cutiIzin->dokumen)) {
            \Storage::disk('public')->delete($cutiIzin->dokumen);
        }

        $cutiIzin->delete();

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Hapus Cuti/Izin',
            'deskripsi' => 'Menghapus pengajuan ' . $jenis . ' dari ' . $namaKaryawan,
        ]);

        return redirect()->route('superadmin.cutiizin.index')
            ->with('success', "Pengajuan {$jenis} dari {$namaKaryawan} berhasil dihapus");
    }

    // Setujui pengajuan
    public function approve(Request $request, $id)
    {
        $cutiIzin = CutiIzin::findOrFail($id);

        // Validasi hanya pending yang bisa diapprove
        if ($cutiIzin->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan ini sudah diproses');
        }

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
            ->with('success', "Pengajuan {$cutiIzin->jenis} dari {$cutiIzin->user->name} berhasil disetujui");
    }

    // Tolak pengajuan
    public function reject(Request $request, $id)
    {
        $cutiIzin = CutiIzin::findOrFail($id);

        // Validasi hanya pending yang bisa direject
        if ($cutiIzin->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan ini sudah diproses');
        }

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
            ->with('success', "Pengajuan {$cutiIzin->jenis} dari {$cutiIzin->user->name} berhasil ditolak");
    }
}