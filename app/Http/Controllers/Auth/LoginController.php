<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LogAktivitas;

class LoginController extends Controller
{
    // Tampilkan form login
    public function showLoginForm()
    {
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
            ])->withInput();
        }

        // Attempt login
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Log aktivitas login
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Login',
                'deskripsi' => 'User berhasil login ke sistem',
            ]);

            // Redirect berdasarkan role
            if (Auth::user()->isSuperAdmin()) {
                return redirect()->intended('/superadmin/dashboard')
                    ->with('success', 'Selamat datang, ' . Auth::user()->name);
            } else {
                return redirect()->intended('/karyawan/dashboard')
                    ->with('success', 'Selamat datang, ' . Auth::user()->name);
            }
        }

        // Login gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    // Proses logout
    public function logout(Request $request)
    {
        // Log aktivitas logout
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Logout',
            'deskripsi' => 'User logout dari sistem',
        ]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda berhasil logout');
    }
}
