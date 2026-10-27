<?php

namespace App\Http\Controllers;
use App\Models\UserApproval;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('home');

            // Tambahkan pesan log untuk memeriksa apakah metode ini terpanggil
    Log::info('showLoginForm method is called');

    return view('home');
    }

    public function dologin(Request $request) {
        // validasi
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (auth()->attempt($credentials)) {

            // buat ulang session login
            $request->session()->regenerate();

            $userRole = auth()->user()->role_id;

            if ($userRole === 1) {
                // jika user superadmin
                return redirect()->intended('/DashboardKantor');
            } elseif ($userRole === 2) {
                // jika user pegawai
                return redirect()->intended('/DashboardPabrik');
            } elseif ($userRole === 3) {
                // jika user unit kerja
                return redirect()->intended('/DashboardPemohon');
            } else {
                // Jika peran tidak dikenali, Anda dapat mengarahkannya ke halaman tertentu atau mengambil tindakan lain sesuai kebutuhan.
                return redirect('/');
            }
        }

        // jika username atau password salah
        // kirimkan session error
        return redirect('/')->withErrors(['error' => "Login failed. Please check your username and password."]);
    }


    public function logout(Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
