<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Divisi;
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
        $divisiList = Divisi::aktif()->orderBy('nama_divisi', 'asc')->get();
        return view('superadmin.karyawan.create', compact('divisiList'));
    }

    // Simpan karyawan baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:users,nik',
            'gelar' => 'nullable|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'alamat' => 'required|string',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nomor_rekening' => 'required|string|max:50',
            'divisi' => 'required|string',
            'jabatan' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
            'aktif_dari' => 'required|date',
            'aktif_sampai' => 'nullable|date|after_or_equal:aktif_dari',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_npwp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_bpjs' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'dokumen_kontrak' => 'nullable|mimes:pdf,doc,docx,jpeg,png,jpg|max:5120',
        ], [
            'name.required' => 'Nama harus diisi',
            'nik.required' => 'NIK harus diisi',
            'nik.size' => 'NIK harus 16 digit',
            'nik.unique' => 'NIK sudah terdaftar',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'alamat.required' => 'Alamat harus diisi',
            'tempat_lahir.required' => 'Tempat lahir harus diisi',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'nomor_rekening.required' => 'Nomor rekening harus diisi',
            'divisi.required' => 'Divisi harus diisi',
            'jabatan.required' => 'Jabatan harus diisi',
            'status.required' => 'Status harus dipilih',
            'aktif_dari.required' => 'Tanggal aktif dari harus diisi',
            'aktif_sampai.after_or_equal' => 'Tanggal aktif sampai harus setelah atau sama dengan tanggal aktif dari',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'foto.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        try {
            $data = [
                'name' => $request->name,
                'nik' => $request->nik,
                'gelar' => $request->gelar,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'alamat' => $request->alamat,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'nomor_rekening' => $request->nomor_rekening,
                'role' => 'karyawan',
                'divisi' => $request->divisi,
                'jabatan' => $request->jabatan,
                'status' => $request->status,
                'aktif_dari' => $request->aktif_dari,
                'aktif_sampai' => $request->aktif_sampai,
            ];

            // Upload foto profil jika ada
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('karyawan/foto', 'public');
                $data['foto'] = $fotoPath;
            }

            // Upload foto KTP jika ada
            if ($request->hasFile('foto_ktp')) {
                $ktpPath = $request->file('foto_ktp')->store('karyawan/ktp', 'public');
                $data['foto_ktp'] = $ktpPath;
            }

            // Upload foto NPWP jika ada
            if ($request->hasFile('foto_npwp')) {
                $npwpPath = $request->file('foto_npwp')->store('karyawan/npwp', 'public');
                $data['foto_npwp'] = $npwpPath;
            }

            // Upload foto BPJS jika ada
            if ($request->hasFile('foto_bpjs')) {
                $bpjsPath = $request->file('foto_bpjs')->store('karyawan/bpjs', 'public');
                $data['foto_bpjs'] = $bpjsPath;
            }

            // Upload dokumen kontrak jika ada
            if ($request->hasFile('dokumen_kontrak')) {
                $kontrakPath = $request->file('dokumen_kontrak')->store('karyawan/kontrak', 'public');
                $data['dokumen_kontrak'] = $kontrakPath;
            }

            $karyawan = User::create($data);

            // Log aktivitas
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Tambah Karyawan',
                'deskripsi' => 'Menambahkan karyawan baru: ' . $karyawan->name . ' (' . $karyawan->jabatan . ')',
            ]);

            return redirect()->route('superadmin.karyawan.index')
                ->with('success', 'Karyawan berhasil ditambahkan');

        } catch (\Exception $e) {
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
        $divisiList = Divisi::aktif()->orderBy('nama_divisi', 'asc')->get();
        return view('superadmin.karyawan.edit', compact('karyawan', 'divisiList'));
    }

    // Update karyawan
    public function update(Request $request, $uuid)
    {
        $karyawan = User::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => ['required', 'string', 'size:16', Rule::unique('users')->ignore($karyawan->id)],
            'gelar' => 'nullable|string|max:50',
            'email' => ['required', 'email', Rule::unique('users')->ignore($karyawan->id)],
            'alamat' => 'required|string',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nomor_rekening' => 'required|string|max:50',
            'divisi' => 'required|string',
            'jabatan' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
            'aktif_dari' => 'required|date',
            'aktif_sampai' => 'nullable|date|after_or_equal:aktif_dari',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_npwp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_bpjs' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'dokumen_kontrak' => 'nullable|mimes:pdf,doc,docx,jpeg,png,jpg|max:5120',
        ]);

        $data = [
            'name' => $request->name,
            'nik' => $request->nik,
            'gelar' => $request->gelar,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nomor_rekening' => $request->nomor_rekening,
            'divisi' => $request->divisi,
            'jabatan' => $request->jabatan,
            'status' => $request->status,
            'aktif_dari' => $request->aktif_dari,
            'aktif_sampai' => $request->aktif_sampai,
        ];

        // Upload foto profil baru jika ada
        if ($request->hasFile('foto')) {
            if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
                Storage::disk('public')->delete($karyawan->foto);
            }
            $fotoPath = $request->file('foto')->store('karyawan/foto', 'public');
            $data['foto'] = $fotoPath;
        }

        // Upload foto KTP baru jika ada
        if ($request->hasFile('foto_ktp')) {
            if ($karyawan->foto_ktp && Storage::disk('public')->exists($karyawan->foto_ktp)) {
                Storage::disk('public')->delete($karyawan->foto_ktp);
            }
            $ktpPath = $request->file('foto_ktp')->store('karyawan/ktp', 'public');
            $data['foto_ktp'] = $ktpPath;
        }

        // Upload foto NPWP baru jika ada
        if ($request->hasFile('foto_npwp')) {
            if ($karyawan->foto_npwp && Storage::disk('public')->exists($karyawan->foto_npwp)) {
                Storage::disk('public')->delete($karyawan->foto_npwp);
            }
            $npwpPath = $request->file('foto_npwp')->store('karyawan/npwp', 'public');
            $data['foto_npwp'] = $npwpPath;
        }

        // Upload foto BPJS baru jika ada
        if ($request->hasFile('foto_bpjs')) {
            if ($karyawan->foto_bpjs && Storage::disk('public')->exists($karyawan->foto_bpjs)) {
                Storage::disk('public')->delete($karyawan->foto_bpjs);
            }
            $bpjsPath = $request->file('foto_bpjs')->store('karyawan/bpjs', 'public');
            $data['foto_bpjs'] = $bpjsPath;
        }

        // Upload dokumen kontrak baru jika ada
        if ($request->hasFile('dokumen_kontrak')) {
            if ($karyawan->dokumen_kontrak && Storage::disk('public')->exists($karyawan->dokumen_kontrak)) {
                Storage::disk('public')->delete($karyawan->dokumen_kontrak);
            }
            $kontrakPath = $request->file('dokumen_kontrak')->store('karyawan/kontrak', 'public');
            $data['dokumen_kontrak'] = $kontrakPath;
        }

        $karyawan->update($data);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Update Karyawan',
            'deskripsi' => 'Mengupdate data karyawan: ' . $karyawan->name . ' (' . $karyawan->jabatan . ')',
        ]);

        return redirect()->route('superadmin.karyawan.index')
            ->with('success', 'Data karyawan berhasil diperbarui');
    }

    // Hapus karyawan
    public function destroy($uuid)
    {
        $karyawan = User::where('uuid', $uuid)->firstOrFail();
        $namaKaryawan = $karyawan->name;
        $jabatanKaryawan = $karyawan->jabatan;

        // Hapus semua file yang terkait
        $filesToDelete = [
            $karyawan->foto,
            $karyawan->foto_ktp,
            $karyawan->foto_npwp,
            $karyawan->foto_bpjs,
            $karyawan->dokumen_kontrak
        ];

        foreach ($filesToDelete as $file) {
            if ($file && Storage::disk('public')->exists($file)) {
                Storage::disk('public')->delete($file);
            }
        }

        $karyawan->delete();

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Hapus Karyawan',
            'deskripsi' => 'Menghapus karyawan: ' . $namaKaryawan . ' (' . $jabatanKaryawan . ')',
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
            'deskripsi' => 'Reset password karyawan: ' . $karyawan->name . ' (' . $karyawan->jabatan . ') menjadi: ' . $defaultPassword,
        ]);

        return redirect()->route('superadmin.karyawan.index')
            ->with('success', 'Password berhasil direset menjadi: ' . $defaultPassword);
    }
}