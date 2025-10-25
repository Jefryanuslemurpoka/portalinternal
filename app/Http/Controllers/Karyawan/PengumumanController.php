<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kategori = $request->input('kategori', 'all');
        $search = $request->input('search', '');
        
        // Query pengumuman
        $query = Pengumuman::orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc');
        
        // Filter kategori (jika ada kolom kategori)
        if ($kategori != 'all') {
            $query->where('kategori', $kategori);
        }
        
        // Search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('konten', 'like', "%{$search}%");
            });
        }
        
        $pengumumanList = $query->paginate(9);
        
        // Hitung statistik
        $statistik = [
            'total' => Pengumuman::count(),
            'penting' => 0, // Karena tidak ada kolom is_penting
            'terbaru' => Pengumuman::where('created_at', '>=', now()->subDays(7))->count()
        ];
        
        return view('karyawan.pengumuman.index', compact('pengumumanList', 'statistik', 'kategori', 'search'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        
        // Get related pengumuman
        $relatedPengumuman = Pengumuman::where('id', '!=', $pengumuman->id)
            ->orderBy('tanggal', 'desc')
            ->limit(3)
            ->get();
        
        return view('karyawan.pengumuman.show', compact('pengumuman', 'relatedPengumuman'));
    }
    
    /**
     * Mark pengumuman as read
     */
    public function markAsRead($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'message' => 'Pengumuman ditandai sudah dibaca'
        ]);
    }
}