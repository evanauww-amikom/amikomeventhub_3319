<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Fungsi menampilkan halaman view formulir
    public function showLogin() {
        return view('auth.login');
    }

    // 2. Fungsi memproses validasi Submit Log In
public function login(Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->role === 'superadmin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'organizer') {
            return $user->organizer && $user->organizer->isApproved()
                ? redirect()->route('organizer.dashboard')
                : redirect()->route('organizer.pending');
        }

        return redirect()->route('home');
    }

    return back()->withErrors([
        'email' => 'Email atau Password yang Anda berikan tidak terdaftar di database kami.',
    ]);
}

    // 3. Fungsi memproses Log Out (Keluar)
    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}