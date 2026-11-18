@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="mb-6">
    <h3 class="text-xl md:text-2xl font-bold text-slate-800 dark:text-white">Selamat datang, {{ auth()->user()->name }}</h3>
    <p class="text-sm text-slate-500 dark:text-slate-400">Berikut ringkasan perpustakaan hari ini.</p>
</div>

{{-- STATISTIK CARDS (TANPA EMOT) --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5 mb-6">
    <div class="bg-white dark:bg-slate-800 rounded-xl p-5 shadow-sm border border-slate-100 dark:border-slate-700">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mb-1">Total Buku</p>
                <p class="text-2xl md:text-3xl font-bold text-slate-800 dark:text-white">{{ $totalBooks }}</p>
            </div>
            <div class="bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 p-3 rounded-lg">
                <i data-lucide="book" class="w-6 h-6"></i>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-xl p-5 shadow-sm border border-slate-100 dark:border-slate-700">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mb-1">Total Siswa</p>
                <p class="text-2xl md:text-3xl font-bold text-slate-800 dark:text-white">{{ $totalStudents }}</p>
            </div>
            <div class="bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 p-3 rounded-lg">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-xl p-5 shadow-sm border border-slate-100 dark:border-slate-700">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mb-1">Buku Dipinjam</p>
                <p class="text-2xl md:text-3xl font-bold text-slate-800 dark:text-white">{{ $totalBorrowed }}</p>
            </div>
            <div class="bg-orange-100 dark:bg-orange-900/50 text-orange-600 dark:text-orange-400 p-3 rounded-lg">
                <i data-lucide="book-open" class="w-6 h-6"></i>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-xl p-5 shadow-sm border border-slate-100 dark:border-slate-700">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mb-1">Terlambat</p>
                <p class="text-2xl md:text-3xl font-bold text-slate-800 dark:text-white">{{ $totalLate }}</p>
            </div>
            <div class="bg-rose-100 dark:bg-rose-900/50 text-rose-600 dark:text-rose-400 p-3 rounded-lg">
                <i data-lucide="alert-triangle" class="w-6 h-6"></i>
            </div>
        </div>
    </div>
</div>

{{-- CHART & TOP BUKU --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5 mb-6">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-5">
        <h4 class="font-bold mb-4 text-slate-800 dark:text-white">Rekap Peminjaman Mingguan</h4>
        <canvas id="weeklyChart" height="200"></canvas>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-5">
        <h4 class="font-bold mb-4 text-slate-800 dark:text-white">Top 3 Buku Populer</h4>
        @if($topBooks->count() > 0 && $topBooks->max('loans_count') > 0)
            <div class="space-y-3">
                @foreach($topBooks as $i => $b)
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400 flex items-center justify-center font-bold text-sm">{{ $i+1 }}</div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-sm text-slate-800 dark:text-white truncate">{{ $b->title }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $b->loans_count }} kali dipinjam</p>
                    </div>
                    <div class="w-20 bg-slate-100 dark:bg-slate-700 rounded-full h-2">
                        <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ ($b->loans_count / $topBooks->max('loans_count')) * 100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-slate-400 text-sm">
                Belum ada data peminjaman.
            </div>
        @endif
    </div>
</div>

{{-- PEMINJAMAN TERBARU & STOK MENIPIS --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-5">
    <div class="md:col-span-2 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
            <h4 class="font-bold text-slate-800 dark:text-white">Peminjaman Terbaru</h4>
            <a href="{{ route('admin.loans.index') }}" class="text-indigo-600 dark:text-indigo-400 text-sm font-medium hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto w-full">
            <table class="w-full text-sm min-w-[500px]">
                <thead class="bg-slate-50 dark:bg-slate-700 text-slate-500 dark:text-slate-300 uppercase text-xs">
                    <tr>
                        <th class="px-5 py-3 text-left">Siswa</th>
                        <th class="px-5 py-3 text-left">Buku</th>
                        <th class="px-5 py-3 text-left">Tgl Pinjam</th>
                        <th class="px-5 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLoans as $loan)
                    <tr class="border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                        <td class="px-5 py-3 font-medium text-slate-800 dark:text-white whitespace-nowrap">{{ $loan->user->name }}</td>
                        <td class="px-5 py-3 text-slate-600 dark:text-slate-300 truncate max-w-[150px]">{{ $loan->book ? $loan->book->title : 'Buku Dihapus' }}</td>
                        <td class="px-5 py-3 text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $loan->loan_date->format('d/m/Y') }}</td>
                        <td class="px-5 py-3">
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $loan->statusColor() }}">
                                {{ $loan->statusLabel() }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center text-slate-400">Belum ada peminjaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="p-5 border-b border-slate-100 dark:border-slate-700">
            <h4 class="font-bold text-slate-800 dark:text-white">Stok Menipis</h4>
            <p class="text-xs text-slate-500 dark:text-slate-400">Buku dengan stok ≤ 3</p>
        </div>
        <div class="p-4 space-y-3">
            @forelse($lowStockBooks as $b)
            <div class="flex items-center gap-3">
                <img src="{{ $b->cover_url }}" class="w-10 h-14 object-cover rounded shadow" alt="">
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-sm text-slate-800 dark:text-white truncate">{{ $b->title }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $b->author }}</p>
                </div>
                <span class="px-2 py-1 bg-rose-100 dark:bg-rose-900/50 text-rose-700 dark:text-rose-400 text-xs font-bold rounded">
                    {{ $b->stock }}
                </span>
            </div>
            @empty
            <div class="text-center py-8 text-slate-400 text-sm">
                Semua stok aman.
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('weeklyChart').getContext('2d');
const isDark = document.documentElement.classList.contains('dark');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Jumlah Peminjaman',
            data: @json($chartData),
            backgroundColor: 'rgba(79, 70, 229, 0.7)',
            borderColor: 'rgba(79, 70, 229, 1)',
            borderWidth: 2,
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1, color: isDark ? '#94a3b8' : '#64748b' },
                grid: { color: isDark ? '#334155' : '#e2e8f0' }
            },
            x: {
                ticks: { color: isDark ? '#94a3b8' : '#64748b' },
                grid: { color: isDark ? '#334155' : '#e2e8f0' }
            }
        }
    }
});
</script>
@endpush