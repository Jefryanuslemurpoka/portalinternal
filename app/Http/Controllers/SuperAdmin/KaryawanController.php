<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KaryawanController extends Controller
{
    // Tampilkan daftar karyawan
    public function index()
    {
        $karyawan = User::where('role', 'karyawan')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('superadmin.karyawan.index', compact('karyawan'));
    }

    // Tampilkan form tambah karyawan
    public function create()
    {
        return view('superadmin.karyawan.create');
    }

    // Simpan karyawan baru
    public function store(Request $request)
    {
        // Debug: Log semua input
        \Log::info('Form Input:', $request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'divisi' => 'required|string',
            'status' => 'required|in:aktif,nonaktif',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'divisi.required' => 'Divisi harus diisi',
            'status.required' => 'Status harus dipilih',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'foto.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'karyawan',
                'divisi' => $request->divisi,
                'status' => $request->status,
            ];

            // Upload foto jika ada
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('karyawan/foto', 'public');
                $data['foto'] = $fotoPath;
            }

            $karyawan = User::create($data);

            // Debug: Log user yang baru dibuat
            \Log::info('User Created:', $karyawan->toArray());

            // Log aktivitas
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Tambah Karyawan',
                'deskripsi' => 'Menambahkan karyawan baru: ' . $karyawan->name,
            ]);

            return redirect()->route('superadmin.karyawan.index')
                ->with('success', 'Karyawan berhasil ditambahkan');

        } catch (\Exception $e) {
            // Debug: Log error
            \Log::error('Error Store Karyawan: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan karyawan: ' . $e->getMessage());
        }
    }

    // Tampilkan detail karyawan
    public function show($uuid)
    {
        $karyawan = User::where('uuid', $uuid)->firstOrFail();
        return view('superadmin.karyawan.show', compact('karyawan'));
    }

    // Tampilkan form edit karyawan
    public function edit($uuid)
    {
        $karyawan = User::where('uuid', $uuid)->firstOrFail();
        return view('superadmin.karyawan.edit', compact('karyawan'));
    }

    // Update karyawan
    public function update(Request $request, $uuid)
    {
        $karyawan = User::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($karyawan->id)],
            'divisi' => 'required|string',
            'status' => 'required|in:aktif,nonaktif',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'divisi.required' => 'Divisi harus diisi',
            'status.required' => 'Status harus dipilih',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'foto.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'divisi' => $request->divisi,
            'status' => $request->status,
        ];

        // Upload foto baru jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
                Storage::disk('public')->delete($karyawan->foto);
            }
            
            $fotoPath = $request->file('foto')->store('karyawan/foto', 'public');
            $data['foto'] = $fotoPath;
        }

        $karyawan->update($data);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Update Karyawan',
            'deskripsi' => 'Mengupdate data karyawan: ' . $karyawan->name,
        ]);

        return redirect()->route('superadmin.karyawan.index')
            ->with('success', 'Data karyawan berhasil diperbarui');
    }

    // Hapus karyawan
    public function destroy($uuid)
    {
        $karyawan = User::where('uuid', $uuid)->firstOrFail();
        $namaKaryawan = $karyawan->name;

        // Hapus foto jika ada
        if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
            Storage::disk('public')->delete($karyawan->foto);
        }

        $karyawan->delete();

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Hapus Karyawan',
            'deskripsi' => 'Menghapus karyawan: ' . $namaKaryawan,
        ]);

        return redirect()->route('superadmin.karyawan.index')
            ->with('success', 'Karyawan berhasil dihapus');
    }

    // Reset password karyawan
    public function resetPassword($uuid)
    {
        $karyawan = User::where('uuid', $uuid)->firstOrFail();
        $defaultPassword = 'karyawan123';
        
        // Update password langsung ke database
        DB::table('users')
            ->where('uuid', $uuid)
            ->update([
                'password' => Hash::make($defaultPassword),
                'updated_at' => now()
            ]);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Reset Password',
            'deskripsi' => 'Reset password karyawan: ' . $karyawan->name . ' menjadi: ' . $defaultPassword,
        ]);

        return redirect()->route('superadmin.karyawan.index')
            ->with('success', 'Password berhasil direset menjadi: ' . $defaultPassword);
    }
}