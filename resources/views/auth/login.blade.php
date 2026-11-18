@extends('layouts.guest')

@section('title', 'Login Siswa')

@section('content')
<div class="w-full max-w-4xl bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl overflow-hidden border border-white/20 grid md:grid-cols-2">
    
    <div class="hidden md:flex flex-col justify-between p-10 text-white relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 bg-white rounded-xl p-2 shadow-lg flex items-center justify-center">
                    <img src="{{ asset('images/logo_smk.jpg') }}" alt="Logo SMK" class="w-full h-full object-contain">
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold tracking-wide">LENDORA</h2>
                    <p class="text-xs text-indigo-200 font-semibold tracking-wider uppercase">Aplikasi Pengelola Peminjaman Buku</p>
                    <p class="text-[11px] text-slate-300 italic mt-1">SMK Negeri 5 Surakarta</p>
                </div>
            </div>
            
            <h1 class="text-3xl font-bold mb-4 leading-tight mt-8">Sistem Peminjaman<br>Buku Perpustakaan</h1>
            <p class="text-indigo-100 mb-8 text-sm leading-relaxed">Platform digital untuk memudahkan siswa mencari, meminjam, dan mengembalikan buku perpustakaan sekolah secara terintegrasi.</p>

            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm">Koleksi Lengkap</h4>
                        <p class="text-xs text-indigo-200 mt-1">Akses katalog buku perpustakaan dengan mudah.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm">Peminjaman Online</h4>
                        <p class="text-xs text-indigo-200 mt-1">Ajukan pinjaman tanpa harus antre panjang.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative z-10 text-xs text-indigo-200 mt-12 border-t border-white/10 pt-4">
            &copy; {{ date('Y') }} LENDORA — Perpustakaan Digital SMK Negeri 5 Surakarta.
        </div>
    </div>

    <div class="flex flex-col justify-center items-center p-6 pt-16 md:p-12 relative bg-white/95 backdrop-blur-sm">
        <a href="{{ route('landing') }}" class="absolute top-4 left-5 md:top-8 md:left-8 text-slate-400 hover:text-slate-800 text-sm font-medium flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali
        </a>

        <div class="w-full max-w-sm">
            <div class="mb-8 text-center">
                <h3 class="text-2xl font-bold text-slate-800">Masuk ke Akun Anda</h3>
                <p class="text-sm text-slate-500 mt-1">Gunakan email dan password yang terdaftar.</p>
            </div>

            @if($errors->any())
                <div class="mb-4 px-4 py-3 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-800 focus:border-transparent outline-none transition text-sm bg-slate-50 focus:bg-white"
                        placeholder="Masukkan email Anda">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-800 focus:border-transparent outline-none transition text-sm bg-slate-50 focus:bg-white"
                        placeholder="Masukkan password Anda">
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-slate-800 focus:ring-slate-800">
                        Ingat saya
                    </label>
                </div>
                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-semibold py-3 rounded-lg transition shadow-lg mt-2 text-sm">
                    Masuk
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-slate-500">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-slate-800 font-bold hover:underline">Daftar</a>
                </p>
            </div>
            <div class="mt-8 pt-6 border-t border-slate-200 flex flex-col items-center gap-3">
                <a href="{{ route('admin.login') }}" class="text-xs text-slate-500 hover:text-slate-800 transition flex items-center gap-1.5 font-medium">
                    <i data-lucide="lock" class="w-3 h-3"></i> Masuk sebagai Admin / Petugas
                </a>
            </div>
        </div>
    </div>
</div>
@endsection