<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function index()
    {
        $user = Auth::user();
        
        return view('karyawan.profil.index', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
        ], [
            'name.required' => 'Nama harus diisi',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'phone.max' => 'Nomor telepon maksimal 20 karakter',
            'address.max' => 'Alamat maksimal 500 karakter',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
        ]);
        
        // Update user data
        $user->update($validated);
        
        return redirect()->route('karyawan.profil.index')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required'
        ], [
            'current_password.required' => 'Password lama harus diisi',
            'new_password.required' => 'Password baru harus diisi',
            'new_password.min' => 'Password baru minimal 8 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
            'new_password_confirmation.required' => 'Konfirmasi password harus diisi'
        ]);
        
        $user = Auth::user();
        
        // Cek password lama
        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->back()
                ->with('error', 'Password lama tidak sesuai!');
        }
        
        // Update password
        $user->update([
            'password' => Hash::make($validated['new_password'])
        ]);
        
        return redirect()->route('karyawan.profil.index')
            ->with('success', 'Password berhasil diubah!');
    }

    /**
     * Update or delete user's profile photo.
     */
    public function updateFoto(Request $request)
    {
        $user = Auth::user();
        
        // Jika ada method DELETE, hapus foto
        if ($request->input('_method') === 'DELETE') {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
                
                $user->update([
                    'foto' => null
                ]);
                
                return redirect()->back()
                    ->with('success', 'Foto profil berhasil dihapus!');
            }
            
            return redirect()->back()
                ->with('error', 'Foto profil tidak ditemukan!');
        }
        
        // Upload foto baru
        $validated = $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ], [
            'foto.required' => 'Foto harus dipilih',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus JPG, JPEG, atau PNG',
            'foto.max' => 'Ukuran foto maksimal 2MB'
        ]);
        
        // Hapus foto lama jika ada
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }
        
        $file = $request->file('foto');
        $fileName = 'profil_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('profil', $fileName, 'public');
        
        $user->update([
            'foto' => $filePath
        ]);
        
        return redirect()->route('karyawan.profil.index')
            ->with('success', 'Foto profil berhasil diperbarui!');
    }
}