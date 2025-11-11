<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Divisi;
use App\Models\LogAktivitas;


class DivisiController extends Controller
{
    public function index()
    {
        $divisi = Divisi::orderBy('nama_divisi', 'asc')->paginate(10);
        
        // Simpan URL sebelumnya jika bukan dari halaman divisi sendiri
        if (!str_contains(url()->previous(), '/divisi') && url()->previous() != url()->current()) {
            session(['divisi_previous_url' => url()->previous()]);
        }
        
        return view('superadmin.divisi.index', compact('divisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_divisi' => 'required|string|max:255|unique:divisi,nama_divisi',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama_divisi.required' => 'Nama divisi harus diisi',
            'nama_divisi.unique' => 'Nama divisi sudah ada',
            'status.required' => 'Status harus dipilih',
        ]);

        $divisi = Divisi::create($request->all());

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Tambah Divisi',
            'deskripsi' => 'Menambahkan divisi baru: ' . $divisi->nama_divisi,
        ]);

        return redirect()->route('superadmin.divisi.index')
            ->with('success', 'Divisi berhasil ditambahkan')
            ->with('redirect_back', true);
    }

    public function update(Request $request, $id)
    {
        $divisi = Divisi::findOrFail($id);

        $request->validate([
            'nama_divisi' => 'required|string|max:255|unique:divisi,nama_divisi,' . $id,
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama_divisi.required' => 'Nama divisi harus diisi',
            'nama_divisi.unique' => 'Nama divisi sudah ada',
            'status.required' => 'Status harus dipilih',
        ]);

        $divisi->update($request->all());

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Update Divisi',
            'deskripsi' => 'Mengupdate divisi: ' . $divisi->nama_divisi,
        ]);

        return redirect()->route('superadmin.divisi.index')
            ->with('success', 'Divisi berhasil diperbarui')
            ->with('redirect_back', true);
    }

    public function destroy($id)
    {
        $divisi = Divisi::findOrFail($id);
        
        // Cek apakah divisi masih digunakan oleh karyawan
        $jumlahKaryawan = \App\Models\User::where('divisi', $divisi->nama_divisi)->count();
        
        if ($jumlahKaryawan > 0) {
            return redirect()->route('superadmin.divisi.index')
                ->with('error', 'Divisi tidak dapat dihapus karena masih digunakan oleh ' . $jumlahKaryawan . ' karyawan');
        }

        $namaDivisi = $divisi->nama_divisi;
        $divisi->delete();

        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Hapus Divisi',
            'deskripsi' => 'Menghapus divisi: ' . $namaDivisi,
        ]);

        return redirect()->route('superadmin.divisi.index')
            ->with('success', 'Divisi berhasil dihapus')
            ->with('redirect_back', true);
    }
}