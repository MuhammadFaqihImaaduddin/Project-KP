<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateDashboard
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            // Jika user belum login, alihkan ke halaman login
            return redirect()->route('login');
        }

        // User sudah login, sekarang cek role-nya
        if (in_array(Auth::user()->role_id, $roles)) {
            // Role user ada di daftar yang diizinkan, lanjutkan request
            return $next($request);
        }

        Log::info('[AUTH_DASHBOARD] - Request URL: ' . $request->fullUrl()); // <<< BARIS INI
        Log::info('[AUTH_DASHBOARD] - Allowed Roles for this route: ' . implode(',', $roles)); // <<< DAN BARIS INI

        // Jika user sudah login TAPI role-nya TIDAK diizinkan untuk rute ini
        // Alihkan ke dashboard utama user (sesuai role-nya) atau halaman akses ditolak
        Log::warning('User ' . Auth::user()->username . ' (Role ID: ' . Auth::user()->role_id . ') mencoba mengakses rute terlarang.');
        // Opsi A: Alihkan ke dashboard utama user
        return redirect()->route('redirect')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        // Opsi B: Alihkan ke halaman akses ditolak (Anda perlu membuat rute dan view ini)
        // return redirect('/access-denied')->with('error', 'Akses Ditolak.');
    }
}
