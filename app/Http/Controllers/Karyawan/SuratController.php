<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    // Tampilkan daftar surat
    public function index(Request $request)
    {
        $query = Surat::query();

        // Filter jenis surat
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter bulan/tahun
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tanggal', $request->bulan)
                  ->whereYear('tanggal', $request->tahun);
        }

        $surat = $query->orderBy('tanggal', 'desc')
                      ->paginate(10);

        // Statistik
        $totalSurat = Surat::count();
        $suratMasuk = Surat::where('jenis', 'masuk')->count();
        $suratKeluar = Surat::where('jenis', 'keluar')->count();

        return view('karyawan.surat.index', compact('surat', 'totalSurat', 'suratMasuk', 'suratKeluar'));
    }

    // Tampilkan form tambah surat
    public function create()
    {
        return view('karyawan.surat.create');
    }

    // Simpan surat baru
    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat' => 'required|string|unique:surat,nomor_surat|max:100',
            'jenis' => 'required|in:masuk,keluar',
            'tanggal' => 'required|date',
            'pengirim' => 'required|string|max:255',
            'penerima' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ], [
            'nomor_surat.required' => 'Nomor surat harus diisi',
            'nomor_surat.unique' => 'Nomor surat sudah terdaftar',
            'jenis.required' => 'Jenis surat harus dipilih',
            'tanggal.required' => 'Tanggal surat harus diisi',
            'pengirim.required' => 'Pengirim harus diisi',
            'penerima.required' => 'Penerima harus diisi',
            'perihal.required' => 'Perihal harus diisi',
            'file_path.file' => 'File harus berupa file',
            'file_path.mimes' => 'Format file: PDF, DOC, DOCX, JPG, PNG',
            'file_path.max' => 'Ukuran file maksimal 5MB',
        ]);

        $data = [
            'nomor_surat' => $request->nomor_surat,
            'jenis' => $request->jenis,
            'tanggal' => $request->tanggal,
            'pengirim' => $request->pengirim,
            'penerima' => $request->penerima,
            'perihal' => $request->perihal,
        ];

        // Upload file jika ada
        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('surat', 'public');
            $data['file_path'] = $filePath;
        }

        $surat = Surat::create($data);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Tambah Surat',
            'deskripsi' => 'Menambahkan surat ' . $surat->jenis . ' No: ' . $surat->nomor_surat,
        ]);

        return redirect()->route('karyawan.surat.index')
            ->with('success', 'Surat berhasil ditambahkan');
    }

    // Tampilkan detail surat
    public function show($id)
    {
        $surat = Surat::findOrFail($id);
        return view('karyawan.surat.show', compact('surat'));
    }

    // Tampilkan form edit surat
    public function edit($id)
    {
        $surat = Surat::findOrFail($id);
        return view('karyawan.surat.edit', compact('surat'));
    }

    // Update surat
    public function update(Request $request, $id)
    {
        $surat = Surat::findOrFail($id);

        $request->validate([
            'nomor_surat' => 'required|string|max:100|unique:surat,nomor_surat,' . $id,
            'jenis' => 'required|in:masuk,keluar',
            'tanggal' => 'required|date',
            'pengirim' => 'required|string|max:255',
            'penerima' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ], [
            'nomor_surat.required' => 'Nomor surat harus diisi',
            'nomor_surat.unique' => 'Nomor surat sudah terdaftar',
            'jenis.required' => 'Jenis surat harus dipilih',
            'tanggal.required' => 'Tanggal surat harus diisi',
            'pengirim.required' => 'Pengirim harus diisi',
            'penerima.required' => 'Penerima harus diisi',
            'perihal.required' => 'Perihal harus diisi',
            'file_path.file' => 'File harus berupa file',
            'file_path.mimes' => 'Format file: PDF, DOC, DOCX, JPG, PNG',
            'file_path.max' => 'Ukuran file maksimal 5MB',
        ]);

        $data = [
            'nomor_surat' => $request->nomor_surat,
            'jenis' => $request->jenis,
            'tanggal' => $request->tanggal,
            'pengirim' => $request->pengirim,
            'penerima' => $request->penerima,
            'perihal' => $request->perihal,
        ];

        // Upload file baru jika ada
        if ($request->hasFile('file_path')) {
            // Hapus file lama
            if ($surat->file_path && Storage::disk('public')->exists($surat->file_path)) {
                Storage::disk('public')->delete($surat->file_path);
            }
            
            $filePath = $request->file('file_path')->store('surat', 'public');
            $data['file_path'] = $filePath;
        }

        $surat->update($data);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Update Surat',
            'deskripsi' => 'Mengupdate surat ' . $surat->jenis . ' No: ' . $surat->nomor_surat,
        ]);

        return redirect()->route('karyawan.surat.index')
            ->with('success', 'Surat berhasil diperbarui');
    }

    // Hapus surat
    public function destroy($id)
    {
        $surat = Surat::findOrFail($id);
        $nomorSurat = $surat->nomor_surat;

        // Hapus file jika ada
        if ($surat->file_path && Storage::disk('public')->exists($surat->file_path)) {
            Storage::disk('public')->delete($surat->file_path);
        }

        $surat->delete();

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Hapus Surat',
            'deskripsi' => 'Menghapus surat No: ' . $nomorSurat,
        ]);

        return redirect()->route('karyawan.surat.index')
            ->with('success', 'Surat berhasil dihapus');
    }

    // Download file surat
    public function download($id)
    {
        $surat = Surat::findOrFail($id);

        if ($surat->file_path && Storage::disk('public')->exists($surat->file_path)) {
            return Storage::disk('public')->download($surat->file_path);
        }

        return redirect()->back()->with('error', 'File tidak ditemukan');
    }
}