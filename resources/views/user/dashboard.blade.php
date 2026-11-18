@extends('layouts.app')

@section('title', 'Dashboard Siswa')
@section('page-title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h3 class="text-xl md:text-2xl font-bold text-slate-800 dark:text-white">Halo, {{ auth()->user()->name }}</h3>
    <p class="text-sm text-slate-500 dark:text-slate-400">Selamat datang di perpustakaan digital LENDORA.</p>
</div>

{{-- STATISTIK SISWA --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-5 mb-6">
    <div class="bg-white dark:bg-slate-800 rounded-xl p-5 shadow-sm border border-slate-100 dark:border-slate-700">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mb-1">Total Peminjaman</p>
                <p class="text-2xl md:text-3xl font-bold text-slate-800 dark:text-white">{{ $totalBorrowed }}</p>
            </div>
            <div class="bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 p-3 rounded-lg">
                <i data-lucide="book" class="w-6 h-6"></i>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-xl p-5 shadow-sm border border-slate-100 dark:border-slate-700">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mb-1">Sedang Dipinjam</p>
                <p class="text-2xl md:text-3xl font-bold text-slate-800 dark:text-white">{{ $totalActive }}</p>
            </div>
            <div class="bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 p-3 rounded-lg">
                <i data-lucide="book-open" class="w-6 h-6"></i>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-xl p-5 shadow-sm border border-slate-100 dark:border-slate-700">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mb-1">Denda Belum Bayar</p>
                <p class="text-2xl md:text-3xl font-bold text-slate-800 dark:text-white">Rp {{ number_format($totalFine, 0, ',', '.') }}</p>
            </div>
            <div class="bg-rose-100 dark:bg-rose-900/50 text-rose-600 dark:text-rose-400 p-3 rounded-lg">
                <i data-lucide="alert-circle" class="w-6 h-6"></i>
            </div>
        </div>
    </div>
</div>

{{-- PEMINJAMAN AKTIF --}}
@if($activeLoan)
<div class="bg-gradient-to-r from-indigo-600 to-indigo-700 dark:from-indigo-800 dark:to-indigo-900 text-white rounded-xl p-4 md:p-6 shadow-lg mb-6">
    <div class="flex items-center gap-3 mb-3">
        <span class="bg-white/20 px-3 py-1 rounded-full text-xs font-medium">Sedang Dipinjam</span>
    </div>
    <div class="flex flex-col md:flex-row gap-4 md:gap-5 items-center md:items-start text-center md:text-left">
        <img src="{{ $activeLoan->book->cover_url }}" class="w-24 h-32 object-cover rounded-lg shadow-lg shrink-0" alt="">
        <div class="flex-1">
            <h4 class="text-xl md:text-2xl font-bold mb-1">{{ $activeLoan->book->title }}</h4>
            <p class="text-indigo-200 mb-4">{{ $activeLoan->book->author }}</p>
            <div class="flex flex-col md:flex-row items-center justify-center md:justify-start gap-4 md:gap-6 text-sm">
                <div>
                    <p class="text-indigo-300 text-xs">Jatuh Tempo</p>
                    <p class="font-semibold">{{ $activeLoan->return_date->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-indigo-300 text-xs">Sisa Hari</p>
                    <p class="font-semibold">
                        @php 
                            $days = now()->startOfDay()->diffInDays($activeLoan->return_date->startOfDay(), false); 
                        @endphp
                        @if($days >= 0) 
                            {{ $days }} hari lagi
                        @else 
                            <span class="text-rose-300">Terlambat {{ abs($days) }} hari</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="bg-white dark:bg-slate-800 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl p-8 text-center mb-6">
    <div class="w-16 h-16 mx-auto bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-3">
        <i data-lucide="book" class="w-8 h-8 text-slate-400"></i>
    </div>
    <p class="text-slate-500 dark:text-slate-400">Anda belum meminjam buku apapun.</p>
    <a href="{{ route('public.catalog') }}"
       class="inline-block mt-3 bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg text-sm font-medium transition">
        Jelajahi Katalog
    </a>
</div>
@endif

{{-- REKOMENDASI BUKU (DENGAN EFEK HOVER ZOOM) --}}
@if(isset($recommended) && $recommended->count() > 0)
<div class="mb-6">
    <h4 class="font-bold text-slate-800 dark:text-white mb-3">Rekomendasi Untukmu</h4>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($recommended as $book)
        <a href="{{ route('public.catalog.show', $book) }}"
           class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-3 md:p-4 hover:shadow-xl transition-all duration-300 group flex flex-col">
            
            {{-- GAMBAR DENGAN EFEK ZOOM --}}
            <div class="aspect-[3/4] rounded-lg mb-3 overflow-hidden bg-slate-100 dark:bg-slate-700">
                <img src="{{ $book->cover_url }}" 
                     class="w-full h-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-110" 
                     alt="">
            </div>
            
            <h5 class="font-semibold text-xs md:text-sm text-slate-800 dark:text-white line-clamp-2 mb-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $book->title }}</h5>
            <p class="text-[10px] md:text-xs text-slate-500 dark:text-slate-400 truncate mt-auto">{{ $book->author }}</p>
        </a>
        @endforeach
    </div>
</div>
@endif
@endsection