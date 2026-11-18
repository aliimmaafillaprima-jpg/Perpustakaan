@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="max-w-3xl w-full">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-5 md:p-6">
        
        <div class="flex flex-col md:flex-row items-center gap-5 mb-6 pb-6 border-b border-slate-100 dark:border-slate-700 text-center md:text-left">
            
            {{-- FOTO PROFIL & TOMBOL HAPUS --}}
            <div class="flex flex-col items-center gap-2">
                <img src="{{ $user->avatar_url }}" class="w-24 h-24 rounded-full object-cover shadow-md border-4 border-indigo-100 dark:border-slate-700" alt="Avatar">
                
                @if($user->avatar)
                    <form action="{{ route('profile.delete-avatar') }}" method="POST" onsubmit="confirmDelete(event, this)">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-rose-500 hover:text-rose-600 font-semibold underline transition">
                            🗑️ Hapus Foto
                        </button>
                    </form>
                @endif
            </div>

            <div>
                <h3 class="text-xl md:text-2xl font-bold text-slate-800 dark:text-white">{{ $user->name }}</h3>
                <p class="text-sm text-slate-500">{{ $user->email }}</p>
                <span class="inline-block mt-2 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold uppercase">
                    {{ $user->role }}
                </span>
            </div>
        </div>

        @if($user->role === 'siswa')
        <div class="grid grid-cols-2 gap-3 md:gap-4 mb-6">
            <div class="bg-slate-50 dark:bg-slate-700 p-3 rounded-lg">
                <p class="text-xs text-slate-500 mb-1">NIS</p>
                <p class="font-semibold dark:text-white text-sm">{{ $user->nis }}</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-700 p-3 rounded-lg">
                <p class="text-xs text-slate-500 mb-1">Kelas / Jurusan</p>
                <p class="font-semibold dark:text-white text-sm">{{ $user->kelas }} - {{ $user->jurusan }}</p>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PUT')
            
            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Foto Profil (Opsional)</label>
                <input type="file" name="avatar" accept="image/*" class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs md:file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 file:cursor-pointer border border-slate-300 dark:border-slate-600 rounded-lg bg-slate-50 dark:bg-slate-700">
                <p class="text-xs text-slate-500 mt-1">Biarkan kosong jika tidak ingin mengganti foto.</p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2.5 text-sm border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">No. Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required class="w-full px-4 py-2.5 text-sm border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                <p class="text-sm font-medium mb-2 text-slate-700 dark:text-white">Ganti Password (Opsional)</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <input type="password" name="password" placeholder="Password Baru" class="w-full px-4 py-2.5 text-sm border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" class="w-full px-4 py-2.5 text-sm border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2.5 rounded-lg shadow transition text-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection