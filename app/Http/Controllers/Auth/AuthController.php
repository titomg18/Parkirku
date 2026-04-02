<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // tampilkan login
    public function login()
    {
        return view('auth.login');
    }

    // proses login
    public function loginPost(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // 🔥 CEK ROLE
            if (auth()->user()->role == 'admin') {
                return redirect('/admin/dashboard')->with('success', 'Login berhasil sebagai Admin!');
            } else {
                return redirect('/petugas/dashboard')->with('success', 'Login berhasil sebagai Petugas!');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah'
        ]);
    }

    // logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil logout');
    }
}