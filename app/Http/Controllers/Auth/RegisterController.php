<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validasi ketat (NIS, Kelas, dan Jurusan)
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users'],
            'nis' => ['required', 'string', 'unique:users', 'regex:/^[0-9]{5,10}$/'],
            'kelas' => ['required', 'string', 'in:X,XI,XII'],
            'jurusan' => ['required', 'string', 'max:50'], // Menggunakan string biasa karena pilihan jurusan banyak
            'phone' => ['required', 'string', 'regex:/^08[0-9]{8,11}$/'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            // Pesan error custom (Bahasa Indonesia)
            'name.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            'name.min' => 'Nama minimal 3 karakter.',
            'email.email' => 'Format email tidak valid (contoh: nama@gmail.com).',
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain.',
            'nis.regex' => 'NIS harus berupa angka (5-10 digit).',
            'nis.unique' => 'NIS ini sudah terdaftar. Silakan gunakan NIS lain.',
            'kelas.in' => 'Kelas tidak valid.',
            'phone.regex' => 'Nomor telepon harus diawali 08 dan berjumlah 10-13 digit.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        $validated['role'] = 'siswa';
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        Auth::login($user);

        return redirect()->route('user.dashboard')->with('success', 'Akun berhasil dibuat! Selamat datang di LENDORA.');
    }
}