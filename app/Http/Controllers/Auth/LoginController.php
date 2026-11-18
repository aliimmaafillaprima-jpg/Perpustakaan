<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Login biasa (Siswa)
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate(['email' => 'required|email', 'password' => 'required']);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return Auth::user()->role === 'admin' 
                ? redirect()->route('admin.dashboard') 
                : redirect()->route('user.dashboard');
        }
        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    // Login khusus Admin
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate(['email' => 'required|email', 'password' => 'required']);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->role === 'admin') {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }
            Auth::logout(); // Jika bukan admin, tendang keluar
            return back()->withErrors(['email' => 'Akses ditolak. Anda bukan Admin.']);
        }
        return back()->withErrors(['email' => 'Kredensial tidak cocok.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}