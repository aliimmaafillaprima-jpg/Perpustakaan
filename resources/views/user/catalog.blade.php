@extends('layouts.app')

@section('title', 'Katalog Buku')
@section('page-title', 'Katalog Buku')

@section('content')
<div class="mb-4 md:mb-5">
    <h3 class="text-lg md:text-xl font-bold text-slate-800 dark:text-white">Jelajahi Koleksi Buku</h3>
    <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400">Cari buku favoritmu berdasarkan judul, penulis, atau penerbit.</p>
</div>

{{-- FORM PENCARIAN & FILTER --}}
<form method="GET" class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 mb-6 flex flex-col md:flex-row gap-3 items-center w-full max-w-full overflow-hidden">
    
    {{-- Input Pencarian Teks --}}
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Judul Buku / Penulis / Penerbit..." 
        class="w-full md:flex-1 min-w-0 max-w-full px-4 py-2.5 text-sm border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
    
    {{-- Dropdown Kategori --}}
    <select name="category" class="w-full md:w-1/4 min-w-0 max-w-full px-4 py-2.5 text-sm border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none truncate">
        <option value="">Semua Kategori</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
    </select>
    
    <button class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-8 py-2.5 text-sm rounded-lg transition whitespace-nowrap">
        Cari Buku
    </button>

    @if(request()->hasAny(['search', 'category']))
        <a href="{{ route('public.catalog') }}" class="w-full md:w-auto text-center text-rose-600 hover:underline text-sm font-medium px-2 py-2">
            Reset
        </a>
    @endif
</form>

{{-- HASIL --}}
<div class="mb-4 flex justify-between items-center">
    <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400">
        Menampilkan <strong class="text-slate-800 dark:text-white">{{ $books->total() }}</strong> buku
        @if(request('search'))
            untuk pencarian "<strong>{{ request('search') }}</strong>"
        @endif
    </p>
</div>

@if($books->count() > 0)
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-5 w-full max-w-full">
    @foreach($books as $book)
    {{-- CARD BUKU DENGAN EFEK HOVER ZOOM --}}
    <a href="{{ route('public.catalog.show', $book) }}"
       class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden hover:shadow-xl transition-all duration-300 group flex flex-col w-full max-w-full">
        
        {{-- GAMBAR BUKU (Diberi class overflow-hidden & transisi) --}}
        <div class="aspect-[3/4] overflow-hidden bg-slate-100 dark:bg-slate-700 relative">
            <img src="{{ $book->cover_url }}" 
                 class="w-full h-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-110" 
                 alt="{{ $book->title }}">
        </div>
        
        <div class="p-3 md:p-4 flex-1 flex flex-col justify-between">
            <div>
                <h5 class="font-semibold text-xs md:text-sm text-slate-800 dark:text-white line-clamp-2 mb-1 leading-tight group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $book->title }}</h5>
                <p class="text-[10px] md:text-xs text-slate-500 dark:text-slate-400 mb-2 truncate">{{ $book->author }}</p>
            </div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-1 mt-auto">
                <span class="text-[10px] md:text-xs text-slate-400 dark:text-slate-500 truncate">{{ $book->category->name }}</span>
                @if($book->stock > 0)
                    <span class="text-[10px] md:text-xs bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400 px-2 py-0.5 rounded text-center w-max">
                        {{ $book->stock }} tersedia
                    </span>
                @else
                    <span class="text-[10px] md:text-xs bg-rose-100 dark:bg-rose-900/50 text-rose-700 dark:text-rose-400 px-2 py-0.5 rounded text-center w-max">
                        Habis
                    </span>
                @endif
            </div>
        </div>
    </a>
    @endforeach
</div>
<div class="mt-6">{{ $books->links() }}</div>
@else
<div class="bg-white dark:bg-slate-800 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl p-8 md:p-12 text-center">
    <i data-lucide="search-x" class="w-12 h-12 mx-auto text-slate-400 mb-3"></i>
    <p class="text-slate-500 dark:text-slate-400 mb-2 text-sm md:text-base">Buku tidak ditemukan.</p>
    <p class="text-xs text-slate-400">Coba gunakan kata kunci lain atau reset filter.</p>
</div>
@endif
@endsection