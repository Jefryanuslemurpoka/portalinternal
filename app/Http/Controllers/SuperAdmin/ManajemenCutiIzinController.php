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
        $query = CutiIzin::with(['user', 'approver']);

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
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('divisi', 'like', "%{$search}%");
            });
        }

        $cutiIzin = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('superadmin.cutiizin.index', compact('cutiIzin'));
    }

    /**
     * Tampilkan form tambah pengajuan cuti/izin
     */
    public function create()
    {
        // Ambil semua karyawan untuk dropdown
        $karyawan = User::where('role', 'karyawan')
            ->orderBy('name', 'asc')
            ->get();

        return view('superadmin.cutiizin.create', compact('karyawan'));
    }

    /**
     * Simpan pengajuan cuti/izin baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jenis' => 'required|in:cuti,izin,sakit',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:1000',
            'file_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'status' => 'nullable|in:pending,disetujui,ditolak',
        ], [
            'user_id.required' => 'Karyawan harus dipilih',
            'user_id.exists' => 'Karyawan tidak ditemukan',
            'jenis.required' => 'Jenis harus dipilih',
            'jenis.in' => 'Jenis tidak valid',
            'tanggal_mulai.required' => 'Tanggal mulai harus diisi',
            'tanggal_mulai.date' => 'Format tanggal mulai tidak valid',
            'tanggal_selesai.required' => 'Tanggal selesai harus diisi',
            'tanggal_selesai.date' => 'Format tanggal selesai tidak valid',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai',
            'alasan.required' => 'Alasan harus diisi',
            'alasan.max' => 'Alasan maksimal 1000 karakter',
            'file_pendukung.file' => 'File harus berupa file',
            'file_pendukung.mimes' => 'File harus berformat PDF, JPG, JPEG, atau PNG',
            'file_pendukung.max' => 'Ukuran file maksimal 2MB',
        ]);

        $data = [
            'user_id' => $request->user_id,
            'jenis' => $request->jenis,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan' => $request->alasan,
            'status' => $request->status ?? 'pending',
        ];

        // Upload file jika ada
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('cuti-izin', $fileName, 'public');
            $data['file_pendukung'] = $filePath;
        }

        // Jika status langsung disetujui/ditolak, set approver
        if (in_array($request->status, ['disetujui', 'ditolak'])) {
            $data['approved_by'] = Auth::id();
            $data['tanggal_persetujuan'] = now();
            
            if ($request->has('keterangan_approval')) {
                $data['keterangan_approval'] = $request->keterangan_approval;
            }
        }

        $cutiIzin = CutiIzin::create($data);

        // Log aktivitas
        $user = User::find($request->user_id);
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Tambah Pengajuan ' . ucfirst($request->jenis),
            'deskripsi' => 'Menambahkan pengajuan ' . $request->jenis . ' untuk ' . $user->name,
        ]);

        return redirect()->route('superadmin.cutiizin.index')
            ->with('success', 'Pengajuan ' . $request->jenis . ' berhasil ditambahkan');
    }

    /**
     * Tampilkan detail pengajuan
     */
    public function show($id)
    {
        $cutiIzin = CutiIzin::with(['user', 'approver'])->findOrFail($id);
        return view('superadmin.cutiizin.show', compact('cutiIzin'));
    }

    /**
     * Tampilkan form edit pengajuan
     */
    public function edit($id)
    {
        $cutiIzin = CutiIzin::with('user')->findOrFail($id);
        
        // Ambil semua karyawan untuk dropdown
        $karyawan = User::where('role', 'karyawan')
            ->orderBy('name', 'asc')
            ->get();

        return view('superadmin.cutiizin.edit', compact('cutiIzin', 'karyawan'));
    }

    /**
     * Update pengajuan cuti/izin
     */
    public function update(Request $request, $id)
    {
        $cutiIzin = CutiIzin::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jenis' => 'required|in:cuti,izin,sakit',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:1000',
            'file_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'status' => 'nullable|in:pending,disetujui,ditolak',
        ], [
            'user_id.required' => 'Karyawan harus dipilih',
            'user_id.exists' => 'Karyawan tidak ditemukan',
            'jenis.required' => 'Jenis harus dipilih',
            'jenis.in' => 'Jenis tidak valid',
            'tanggal_mulai.required' => 'Tanggal mulai harus diisi',
            'tanggal_mulai.date' => 'Format tanggal mulai tidak valid',
            'tanggal_selesai.required' => 'Tanggal selesai harus diisi',
            'tanggal_selesai.date' => 'Format tanggal selesai tidak valid',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai',
            'alasan.required' => 'Alasan harus diisi',
            'alasan.max' => 'Alasan maksimal 1000 karakter',
            'file_pendukung.file' => 'File harus berupa file',
            'file_pendukung.mimes' => 'File harus berformat PDF, JPG, JPEG, atau PNG',
            'file_pendukung.max' => 'Ukuran file maksimal 2MB',
        ]);

        $data = [
            'user_id' => $request->user_id,
            'jenis' => $request->jenis,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan' => $request->alasan,
        ];

        // Update status jika ada
        if ($request->has('status')) {
            $data['status'] = $request->status;

            // Jika status diubah ke disetujui/ditolak, set approver
            if (in_array($request->status, ['disetujui', 'ditolak']) && $cutiIzin->status == 'pending') {
                $data['approved_by'] = Auth::id();
                $data['tanggal_persetujuan'] = now();
            }
        }

        // Update keterangan approval jika ada
        if ($request->has('keterangan_approval')) {
            $data['keterangan_approval'] = $request->keterangan_approval;
        }

        // Upload file baru jika ada
        if ($request->hasFile('file_pendukung')) {
            // Hapus file lama jika ada
            if ($cutiIzin->file_pendukung && Storage::disk('public')->exists($cutiIzin->file_pendukung)) {
                Storage::disk('public')->delete($cutiIzin->file_pendukung);
            }

            $file = $request->file('file_pendukung');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('cuti-izin', $fileName, 'public');
            $data['file_pendukung'] = $filePath;
        }

        // Hapus file jika checkbox remove dicentang
        if ($request->has('remove_file') && $request->remove_file == '1') {
            if ($cutiIzin->file_pendukung && Storage::disk('public')->exists($cutiIzin->file_pendukung)) {
                Storage::disk('public')->delete($cutiIzin->file_pendukung);
            }
            $data['file_pendukung'] = null;
        }

        $cutiIzin->update($data);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Update Pengajuan ' . ucfirst($cutiIzin->jenis),
            'deskripsi' => 'Mengupdate pengajuan ' . $cutiIzin->jenis . ' dari ' . $cutiIzin->user->name,
        ]);

        return redirect()->route('superadmin.cutiizin.index')
            ->with('success', 'Pengajuan ' . $cutiIzin->jenis . ' berhasil diupdate');
    }

    /**
     * Setujui pengajuan cuti/izin
     */
    public function approve(Request $request, $id)
    {
        $cutiIzin = CutiIzin::findOrFail($id);

        if ($cutiIzin->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan ini sudah diproses sebelumnya');
        }

        $cutiIzin->update([
            'status' => 'disetujui',
            'approved_by' => Auth::id(),
            'tanggal_persetujuan' => now(),
            'keterangan_approval' => $request->keterangan_approval ?? null
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
            'keterangan_approval' => 'required|string|max:500'
        ], [
            'keterangan_approval.required' => 'Alasan penolakan harus diisi',
            'keterangan_approval.max' => 'Alasan penolakan maksimal 500 karakter'
        ]);

        $cutiIzin = CutiIzin::findOrFail($id);

        if ($cutiIzin->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan ini sudah diproses sebelumnya');
        }

        $cutiIzin->update([
            'status' => 'ditolak',
            'approved_by' => Auth::id(),
            'tanggal_persetujuan' => now(),
            'keterangan_approval' => $request->keterangan_approval
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
        $query = CutiIzin::with(['user', 'approver']);

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
            'total_sakit' => $data->where('jenis', 'sakit')->count(),
        ];

        return view('superadmin.cutiizin.laporan', compact('data', 'statistik'));
    }
}