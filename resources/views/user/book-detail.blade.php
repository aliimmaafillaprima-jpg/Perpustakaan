@extends('layouts.app')

@section('title', $book->title)
@section('page-title', 'Detail Buku')

@section('content')
{{-- TOMBOL BACK MANUAL DIHAPUS --}}

<div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-4 md:p-6 grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 w-full">
    {{-- GAMBAR COVER --}}
    <div class="md:col-span-1 w-full max-w-[200px] md:max-w-full mx-auto">
        <div class="aspect-[3/4] rounded-xl overflow-hidden shadow-lg bg-slate-100 dark:bg-slate-700">
            <img src="{{ $book->cover_url }}" class="w-full h-full object-cover" alt="Cover {{ $book->title }}">
        </div>
    </div>

    {{-- INFO BUKU --}}
    <div class="md:col-span-2 flex flex-col">
        <h2 class="text-xl md:text-2xl font-bold text-slate-800 dark:text-white mb-1 md:mb-2">{{ $book->title }}</h2>
        <p class="text-sm md:text-base text-slate-500 dark:text-slate-400 mb-4 md:mb-5">oleh <strong class="text-slate-700 dark:text-slate-300">{{ $book->author }}</strong></p>

        <div class="grid grid-cols-2 gap-3 md:gap-4 mb-5 text-sm bg-slate-50 dark:bg-slate-700/50 p-3 rounded-lg">
            <div>
                <p class="text-slate-400 dark:text-slate-500 text-xs mb-0.5">Kategori</p>
                <p class="font-medium text-slate-800 dark:text-slate-200 truncate">{{ $book->category->name }}</p>
            </div>
            <div>
                <p class="text-slate-400 dark:text-slate-500 text-xs mb-0.5">Penerbit</p>
                <p class="font-medium text-slate-800 dark:text-slate-200 truncate">{{ $book->publisher ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-400 dark:text-slate-500 text-xs mb-0.5">Tahun Terbit</p>
                <p class="font-medium text-slate-800 dark:text-slate-200">{{ $book->year ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-400 dark:text-slate-500 text-xs mb-0.5">ISBN</p>
                <p class="font-medium text-slate-800 dark:text-slate-200 truncate">{{ $book->isbn ?? '-' }}</p>
            </div>
            <div class="col-span-2 pt-2 border-t border-slate-200 dark:border-slate-600 mt-1">
                <p class="text-slate-400 dark:text-slate-500 text-xs mb-0.5">Stok Ketersediaan</p>
                @if($book->stock > 0)
                    <p class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $book->stock }} buku tersedia ✅</p>
                @else
                    <p class="font-semibold text-rose-600 dark:text-rose-400">Stok habis ❌</p>
                @endif
            </div>
        </div>

        <div class="mb-6">
            <p class="text-slate-500 dark:text-slate-400 text-xs mb-1 font-semibold uppercase tracking-wider">Sinopsis</p>
            <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed text-justify">{{ $book->description ?? 'Belum ada deskripsi untuk buku ini.' }}</p>
        </div>

        {{-- TOMBOL AKSI --}}
        <div class="mt-auto">
            @if($book->stock > 0)
                @auth
                    @if($hasActiveLoan)
                        <div class="bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 rounded-lg p-3 text-sm text-amber-700 dark:text-amber-400 flex items-center gap-2">
                            Anda sudah memiliki peminjaman aktif untuk buku ini.
                        </div>
                    @else
                        <a href="{{ route('user.loans.create', $book) }}"
                           class="inline-block w-full md:w-auto text-center bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded-lg shadow-lg transition transform hover:-translate-y-0.5">
                            Ajukan Peminjaman
                        </a>
                    @endif
                @else
                    <div class="flex flex-col md:flex-row items-center gap-3">
                        <a href="{{ route('login') }}"
                           class="inline-block w-full md:w-auto text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-lg shadow-lg transition transform hover:-translate-y-0.5">
                            🔒 Login untuk Meminjam
                        </a>
                        <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 text-center md:text-left">
                            Belum punya akun? <a href="{{ route('register') }}" class="text-indigo-600 dark:text-indigo-400 font-semibold hover:underline">Daftar sekarang</a>.
                        </p>
                    </div>
                @endauth
            @else
                <button disabled class="w-full md:w-auto bg-slate-300 dark:bg-slate-700 text-slate-500 dark:text-slate-400 font-semibold px-6 py-3 rounded-lg cursor-not-allowed">
                    Stok Habis
                </button>
            @endif
        </div>
    </div>
</div>

{{-- BUKU TERKAIT --}}
@if(isset($relatedBooks) && $relatedBooks->count() > 0)
<div>
    <h4 class="font-bold text-slate-800 dark:text-white mb-3 text-sm md:text-base"> Buku Serupa</h4>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
        @foreach($relatedBooks as $rel)
        <a href="{{ route('public.catalog.show', $rel) }}"
           class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-2 md:p-3 hover:shadow-md transition group">
            <div class="aspect-[3/4] rounded-lg overflow-hidden mb-2 md:mb-3 bg-slate-100 dark:bg-slate-700">
                <img src="{{ $rel->cover_url }}" class="w-full h-full object-cover group-hover:scale-105 transition" alt="">
            </div>
            <h5 class="font-semibold text-xs md:text-sm text-slate-800 dark:text-white line-clamp-2 mb-1 leading-tight">{{ $rel->title }}</h5>
            <p class="text-[10px] md:text-xs text-slate-500 dark:text-slate-400 truncate">{{ $rel->author }}</p>
        </a>
        @endforeach
    </div>
</div>
@endif
@endsection