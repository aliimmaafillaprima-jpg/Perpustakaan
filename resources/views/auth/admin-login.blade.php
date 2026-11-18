@extends('layouts.guest')

@section('title', 'Admin Login')

@section('content')
<div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-10">
    <div class="text-center mb-6">
        <div class="w-20 h-20 bg-white rounded-full mx-auto mb-4 border-2 border-slate-100 flex items-center justify-center shadow-sm">
            <img src="{{ asset('images/logo_smk.jpg') }}" alt="Logo SMK" class="w-16 h-16 object-contain">
        </div>
        <h3 class="text-2xl font-bold text-slate-800">Login Admin</h3>
        <p class="text-slate-500 text-sm mt-1">Khusus Petugas Perpustakaan</p>
    </div>

    @if($errors->any())
        <div class="mb-4 px-4 py-3 bg-rose-100 border-l-4 border-rose-500 text-rose-700 rounded text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Email Admin</label>
            <input type="email" name="email" required autofocus
                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-800 outline-none transition text-sm"
                placeholder="Masukkan email Anda">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
            <input type="password" name="password" required
                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-800 outline-none transition text-sm"
                placeholder="••••••••">
        </div>
        <button type="submit"
            class="w-full bg-slate-800 hover:bg-slate-900 text-white font-semibold py-3 rounded-lg transition shadow-lg">
            Masuk sebagai Admin
        </button>
    </form>

    <p class="mt-5 text-center text-sm text-slate-500">
        <a href="{{ route('landing') }}" class="text-slate-800 font-semibold hover:underline">← Kembali ke Beranda</a>
    </p>
</div>
@endsection