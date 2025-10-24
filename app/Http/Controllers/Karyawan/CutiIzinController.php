<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\CutiIzin;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // ✅ Tambahkan ini

class CutiIzinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'all'); // all, pending, disetujui, ditolak
        $jenis = $request->input('jenis', 'all'); // all, cuti, izin, sakit
        
        $query = CutiIzin::with('user')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');
        
        if ($status != 'all') {
            $query->where('status', $status);
        }
        
        if ($jenis != 'all') {
            $query->where('jenis', $jenis);
        }
        
        $pengajuanList = $query->paginate(10);
        
        // Hitung statistik
        $statistik = [
            'total' => CutiIzin::where('user_id', Auth::id())->count(),
            'pending' => CutiIzin::where('user_id', Auth::id())->where('status', 'pending')->count(),
            'disetujui' => CutiIzin::where('user_id', Auth::id())->where('status', 'disetujui')->count(),
            'ditolak' => CutiIzin::where('user_id', Auth::id())->where('status', 'ditolak')->count(),
            'sisa_cuti' => Auth::user()->sisa_cuti ?? 12 // Default 12 hari per tahun
        ];
        
        return view('karyawan.cutiizin.index', compact('pengajuanList', 'statistik', 'status', 'jenis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis' => 'required|in:cuti,izin,sakit',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:500',
            'file_pendukung' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ], [
            'jenis.required' => 'Jenis pengajuan harus dipilih',
            'jenis.in' => 'Jenis pengajuan tidak valid',
            'tanggal_mulai.required' => 'Tanggal mulai harus diisi',
            'tanggal_mulai.date' => 'Format tanggal mulai tidak valid',
            'tanggal_selesai.required' => 'Tanggal selesai harus diisi',
            'tanggal_selesai.date' => 'Format tanggal selesai tidak valid',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai',
            'alasan.required' => 'Alasan harus diisi',
            'alasan.max' => 'Alasan maksimal 500 karakter',
            'file_pendukung.mimes' => 'File harus berformat JPG, JPEG, PNG, atau PDF',
            'file_pendukung.max' => 'Ukuran file maksimal 2MB'
        ]);
        
        // Hitung jumlah hari
        $tanggalMulai = Carbon::parse($validated['tanggal_mulai']);
        $tanggalSelesai = Carbon::parse($validated['tanggal_selesai']);
        $jumlahHari = $tanggalMulai->diffInDays($tanggalSelesai) + 1;
        
        // Cek apakah ada pengajuan yang bentrok
        $bentrok = CutiIzin::where('user_id', Auth::id())
            ->where('status', '!=', 'ditolak')
            ->where(function($query) use ($validated) {
                $query->whereBetween('tanggal_mulai', [$validated['tanggal_mulai'], $validated['tanggal_selesai']])
                    ->orWhereBetween('tanggal_selesai', [$validated['tanggal_mulai'], $validated['tanggal_selesai']])
                    ->orWhere(function($q) use ($validated) {
                        $q->where('tanggal_mulai', '<=', $validated['tanggal_mulai'])
                          ->where('tanggal_selesai', '>=', $validated['tanggal_selesai']);
                    });
            })
            ->exists();
        
        if ($bentrok) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Sudah ada pengajuan pada tanggal tersebut yang masih pending atau disetujui!');
        }
        
        // Cek sisa cuti jika jenis adalah cuti
        if ($validated['jenis'] == 'cuti') {
            $sisaCuti = Auth::user()->sisa_cuti ?? 12;
            $cutiTerpakai = CutiIzin::where('user_id', Auth::id())
                ->where('jenis', 'cuti')
                ->where('status', 'disetujui')
                ->whereYear('tanggal_mulai', date('Y'))
                ->sum('jumlah_hari');
            
            $sisaCutiAktual = $sisaCuti - $cutiTerpakai;
            
            if ($jumlahHari > $sisaCutiAktual) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Sisa cuti Anda hanya {$sisaCutiAktual} hari. Pengajuan membutuhkan {$jumlahHari} hari!");
            }
        }
        
        // Handle file upload
        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('cutiizin', $fileName, 'public');
        }
        
        // Buat pengajuan baru
        CutiIzin::create([
            'user_id' => Auth::id(),
            'jenis' => $validated['jenis'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'jumlah_hari' => $jumlahHari,
            'alasan' => $validated['alasan'],
            'file_pendukung' => $filePath,
            'status' => 'pending',
            'tanggal_pengajuan' => now()
        ]);
        
        return redirect()->route('karyawan.cutiizin.index')
            ->with('success', 'Pengajuan ' . ucfirst($validated['jenis']) . ' berhasil diajukan! Menunggu persetujuan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cutiIzin = CutiIzin::with('user')->findOrFail($id);
        
        // Pastikan hanya pemilik yang bisa lihat
        if ($cutiIzin->user_id != Auth::id() && Auth::user()->role != 'admin') {
            abort(403, 'Anda tidak memiliki akses ke pengajuan ini');
        }
        
        return view('karyawan.cutiizin.show', compact('cutiIzin'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cutiIzin = CutiIzin::findOrFail($id);
        
        // Pastikan hanya pemilik yang bisa hapus dan status masih pending
        if ($cutiIzin->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus pengajuan ini!');
        }
        
        if ($cutiIzin->status != 'pending') {
            return redirect()->back()->with('error', 'Hanya pengajuan dengan status pending yang bisa dihapus!');
        }
        
        // Hapus file jika ada
        if ($cutiIzin->file_pendukung) {
            Storage::disk('public')->delete($cutiIzin->file_pendukung); // ✅ sekarang sudah di-import
        }
        
        $cutiIzin->delete();
        
        return redirect()->route('karyawan.cutiizin.index')
            ->with('success', 'Pengajuan berhasil dibatalkan!');
    }
    
    /**
     * Get remaining leave days
     */
    public function getSisaCuti()
    {
        $sisaCuti = Auth::user()->sisa_cuti ?? 12;
        $cutiTerpakai = CutiIzin::where('user_id', Auth::id())
            ->where('jenis', 'cuti')
            ->where('status', 'disetujui')
            ->whereYear('tanggal_mulai', date('Y'))
            ->sum('jumlah_hari');
        
        $sisaCutiAktual = $sisaCuti - $cutiTerpakai;
        
        return response()->json([
            'sisa_cuti' => $sisaCutiAktual,
            'cuti_terpakai' => $cutiTerpakai,
            'total_cuti' => $sisaCuti
        ]);
    }
}
