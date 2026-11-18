@extends('layouts.app')

@section('title', 'Kelola Buku')
@section('page-title', 'Kelola Buku')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-5 gap-3">
    <div>
        <h3 class="text-xl font-bold text-slate-800 dark:text-white"> Daftar Buku</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400">Total {{ $books->total() }} buku terdaftar.</p>
    </div>
    <a href="{{ route('admin.books.create') }}"
       class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-5 py-2.5 rounded-lg shadow transition flex items-center justify-center gap-2 w-full md:w-auto">
        <span>+</span> Tambah Buku
    </a>
</div>

{{-- FILTER KATEGORI (Teks dipendekan) --}}
<form method="GET" class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 mb-5 flex flex-col md:flex-row gap-3 w-full min-w-0">
    <select name="category" class="w-full md:flex-1 min-w-0 max-w-full px-4 py-2.5 text-sm border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none truncate">
        {{-- Teks diperpendek --}}
        <option value="">Semua Kategori</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
    </select>
    <button type="submit" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2.5 text-sm rounded-lg transition">
        Terapkan Filter
    </button>
    @if(request('category'))
        <a href="{{ route('admin.books.index') }}" class="w-full md:w-auto text-center bg-rose-100 text-rose-600 hover:bg-rose-200 dark:bg-rose-900/30 dark:text-rose-400 font-medium px-4 py-2.5 rounded-lg transition">
            ✕ Reset
        </a>
    @endif
</form>

<div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-sm min-w-[700px]">
            <thead class="bg-indigo-50 dark:bg-slate-700 text-indigo-700 dark:text-indigo-300 uppercase text-xs">
                <tr>
                    <th class="px-5 py-3 text-left">No</th>
                    <th class="px-5 py-3 text-left">Cover</th>
                    <th class="px-5 py-3 text-left">Judul</th>
                    <th class="px-5 py-3 text-left">Penulis</th>
                    <th class="px-5 py-3 text-left">Kategori</th>
                    <th class="px-5 py-3 text-left">Stok</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $i => $book)
                <tr class="border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    <td class="px-5 py-3 text-slate-500">{{ $books->firstItem() + $i }}</td>
                    <td class="px-5 py-3">
                        <img src="{{ $book->cover_url }}" class="w-12 h-16 object-cover rounded shadow border" alt="">
                    </td>
                    <td class="px-5 py-3">
                        <p class="font-semibold text-slate-800 dark:text-white">{{ $book->title }}</p>
                        <p class="text-xs text-slate-400">{{ $book->publisher ?? '-' }} • {{ $book->year ?? '-' }}</p>
                    </td>
                    <td class="px-5 py-3 text-slate-600 dark:text-slate-300">{{ $book->author }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-1 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400 rounded text-xs whitespace-nowrap">
                            {{ $book->category->name }}
                        </span>
                    </td>
                    <td class="px-5 py-3 font-semibold text-slate-800 dark:text-white">{{ $book->stock }}</td>
                    <td class="px-5 py-3">
                        @if($book->stock > 0)
                            <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400 rounded-full text-xs font-medium whitespace-nowrap">
                                Tersedia
                            </span>
                        @else
                            <span class="px-3 py-1 bg-rose-100 dark:bg-rose-900/50 text-rose-700 dark:text-rose-400 rounded-full text-xs font-medium whitespace-nowrap">
                                Habis
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.books.edit', $book) }}"
                               class="px-3 py-1.5 bg-indigo-500 hover:bg-indigo-600 text-white rounded text-xs transition whitespace-nowrap">
                                Edit
                            </a>
                            
                            <form action="{{ route('admin.books.destroy', $book) }}" method="POST" onsubmit="confirmDelete(event, this)">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-rose-500 hover:bg-rose-600 text-white rounded text-xs transition whitespace-nowrap">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-12 text-center text-slate-400">
                        <div class="text-5xl mb-3"></div>
                        <p>Belum ada buku. Yuk, tambahkan buku pertama!</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($books->hasPages())
        <div class="p-4 border-t border-slate-100 dark:border-slate-700">{{ $books->links() }}</div>
    @endif
</div>
@endsection