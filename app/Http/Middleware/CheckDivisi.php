<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckDivisi
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $divisi): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Cek apakah user adalah Karyawan
        if (!Auth::user()->isKaryawan()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini');
        }

        // Cek apakah divisi user sesuai
        if (strtoupper(Auth::user()->divisi) !== strtoupper($divisi)) {
            abort(403, 'Anda tidak memiliki akses ke halaman divisi ini');
        }

        return $next($request);
    }
}