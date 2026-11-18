@extends('layouts.app')

@section('title', 'Data Siswa')
@section('page-title', 'Data Siswa')

@section('content')
<div class="flex justify-between items-center mb-5 flex-wrap gap-3">
    <div>
        <h3 class="text-xl font-bold text-slate-800 dark:text-white">Daftar Siswa</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400">Total {{ $students->total() }} siswa terdaftar.</p>
    </div>
    <a href="{{ route('admin.students.create') }}"
       class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-5 py-2.5 rounded-lg shadow transition flex items-center gap-2">
        <span>+</span> Tambah Siswa
    </a>
</div>

{{-- TABEL --}}
<div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[700px]">
            <thead class="bg-indigo-50 dark:bg-slate-700 text-indigo-700 dark:text-indigo-300 uppercase text-xs">
                <tr>
                    <th class="px-5 py-3 text-left">No</th>
                    <th class="px-5 py-3 text-left">NIS</th>
                    <th class="px-5 py-3 text-left">Nama</th>
                    <th class="px-5 py-3 text-left">Kelas</th>
                    <th class="px-5 py-3 text-left">Jurusan</th>
                    <th class="px-5 py-3 text-left">Kontak</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $i => $student)
                <tr class="border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    <td class="px-5 py-3 text-slate-500">{{ $students->firstItem() + $i }}</td>
                    <td class="px-5 py-3 font-mono text-slate-600 dark:text-slate-300">{{ $student->nis }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $student->avatar_url }}" class="w-9 h-9 rounded-full object-cover" alt="">
                            <div>
                                <p class="font-semibold text-slate-800 dark:text-white">{{ $student->name }}</p>
                                <p class="text-xs text-slate-400">{{ $student->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-1 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400 rounded text-xs">
                            {{ $student->kelas }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-1 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400 rounded text-xs">
                            {{ $student->jurusan ?? '-' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-slate-500 dark:text-slate-400 text-xs">{{ $student->phone ?? '-' }}</td>
                    <td class="px-5 py-3">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.students.edit', $student) }}"
                               class="px-3 py-1.5 bg-indigo-500 hover:bg-indigo-600 text-white rounded text-xs transition">
                                Edit
                            </a>
                            <form action="{{ route('admin.students.destroy', $student) }}" method="POST" onsubmit="confirmDelete(event, this)">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-rose-500 hover:bg-rose-600 text-white rounded text-xs transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                        <p>Belum ada siswa terdaftar.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($students->hasPages())
        <div class="p-4 border-t border-slate-100 dark:border-slate-700">{{ $students->links() }}</div>
    @endif
</div>
@endsection