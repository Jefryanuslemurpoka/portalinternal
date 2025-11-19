<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    // Tampilkan form login
    public function showLoginForm()
    {
        // Pastikan user yang sudah login tidak bisa akses login page
        if (Auth::check()) {
            return redirect($this->redirectTo());
        }

        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        // Ambil credentials
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // Cek status user aktif
        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user && $user->status === 'nonaktif') {
            return back()->withErrors([
                'email' => 'Akun Anda tidak aktif. Hubungi administrator.',
            ])->withInput($request->only('email'));
        }

        // Attempt login
        if (Auth::attempt($credentials, $remember)) {
            // REGENERATE SESSION untuk keamanan
            $request->session()->regenerate();
            
            // ✅ CRITICAL FIX: Simpan session secara eksplisit
            $request->session()->save();

            // Log aktivitas login
            try {
                LogAktivitas::create([
                    'user_id' => Auth::id(),
                    'aktivitas' => 'Login',
                    'deskripsi' => 'User berhasil login ke sistem dari IP: ' . $request->ip(),
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to log activity: ' . $e->getMessage());
            }

            // Redirect berdasarkan role menggunakan redirectTo()
            return redirect()->intended($this->redirectTo())
                ->with('success', 'Selamat datang, ' . Auth::user()->name);
        }

        // Login gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    // Proses logout
    public function logout(Request $request)
    {
        // Log aktivitas logout
        try {
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Logout',
                'deskripsi' => 'User logout dari sistem',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log logout activity: ' . $e->getMessage());
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil logout');
    }

    /**
     * Tentukan redirect URL berdasarkan role dan divisi user
     */
    protected function redirectTo()
    {
        $user = auth()->user();

        // Redirect untuk Super Admin
        if ($user->isSuperAdmin()) {
            return '/superadmin/dashboard';
        }

        // Redirect untuk Karyawan berdasarkan divisi
        if ($user->isKaryawan()) {
            // Finance divisi ke dashboard finance
            if (strtoupper($user->divisi) === 'FINANCE') {
                return '/finance/dashboard';
            }
            
            // Default ke dashboard karyawan (untuk IT dan divisi lain)
            return '/karyawan/dashboard';
        }

        // Fallback jika tidak ada role yang cocok
        return '/login';
    }
}