<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengumuman;
use App\Models\LogAktivitas;

class PengumumanController extends Controller
{
    // Tampilkan daftar pengumuman
    public function index()
    {
        $pengumuman = Pengumuman::with('creator')
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('superadmin.pengumuman.index', compact('pengumuman'));
    }

    // Tampilkan form tambah pengumuman
    public function create()
    {
        return view('superadmin.pengumuman.create');
    }

    // Simpan pengumuman baru
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'tanggal' => 'required|date',
        ], [
            'judul.required' => 'Judul pengumuman harus diisi',
            'konten.required' => 'Konten pengumuman harus diisi',
            'tanggal.required' => 'Tanggal pengumuman harus diisi',
        ]);

        $pengumuman = Pengumuman::create([
            'judul' => $request->judul,
            'konten' => $request->konten,
            'tanggal' => $request->tanggal,
            'created_by' => auth()->id(),
        ]);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Tambah Pengumuman',
            'deskripsi' => 'Menambahkan pengumuman: ' . $pengumuman->judul,
        ]);

        return redirect()->route('superadmin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil ditambahkan');
    }

    // Tampilkan form edit pengumuman
    public function edit($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        return view('superadmin.pengumuman.edit', compact('pengumuman'));
    }

    // Update pengumuman
    public function update(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'tanggal' => 'required|date',
        ], [
            'judul.required' => 'Judul pengumuman harus diisi',
            'konten.required' => 'Konten pengumuman harus diisi',
            'tanggal.required' => 'Tanggal pengumuman harus diisi',
        ]);

        $pengumuman->update([
            'judul' => $request->judul,
            'konten' => $request->konten,
            'tanggal' => $request->tanggal,
        ]);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Update Pengumuman',
            'deskripsi' => 'Mengupdate pengumuman: ' . $pengumuman->judul,
        ]);

        return redirect()->route('superadmin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui');
    }

    // Hapus pengumuman
    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $judul = $pengumuman->judul;

        $pengumuman->delete();

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Hapus Pengumuman',
            'deskripsi' => 'Menghapus pengumuman: ' . $judul,
        ]);

        return redirect()->route('superadmin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus');
    }
}