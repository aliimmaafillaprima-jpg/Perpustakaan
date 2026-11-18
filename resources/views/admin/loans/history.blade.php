@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')
@section('page-title', 'Riwayat & Cetak Laporan')

@section('content')
<style>
    @media print {
        @page { size: landscape; margin: 15mm; }
        aside, header, .no-print { display: none !important; }
        .md\:ml-64 { margin-left: 0 !important; }
        body { background: white !important; color: black !important; }
        .bg-white { box-shadow: none !important; border: none !important; }
        table { width: 100% !important; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #000 !important; padding: 6px !important; color: black !important; }
        .print-header { display: block !important; text-align: center; margin-bottom: 20px; }
        tfoot { display: table-footer-group; }
    }
</style>

<div class="print-header hidden">
    <h2 style="font-size: 18px; font-weight: bold; margin: 0;">LAPORAN PERPUSTAKAAN LENDORA</h2>
    <h3 style="font-size: 16px; margin: 5px 0;">SMK Negeri 5 Surakarta</h3>
    <hr style="border: 1px solid black; margin-bottom: 10px;">
</div>

{{-- FORM FILTER TANGGAL & TOMBOL AKSI --}}
<div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-4 md:p-5 mb-5 no-print">
    <form method="GET" action="{{ route('admin.loans.history') }}" class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-4 py-2 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-4 py-2 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2 rounded-lg transition h-[42px]">
                    Terapkan Filter
                </button>
            </div>
        </div>
        
        {{-- TOMBOL UNDUH PDF & CETAK --}}
        <div class="flex gap-2 w-full md:w-auto mt-4 md:mt-0">
            <a href="{{ route('admin.loans.history.pdf', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="w-full md:w-auto bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-lg text-sm transition flex justify-center items-center gap-2 shadow">
                Unduh PDF
            </a>
            <button onclick="window.print()" class="w-full md:w-auto bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-lg text-sm transition flex justify-center items-center gap-2 shadow">
                Cetak
            </button>
        </div>
    </form>
</div>

{{-- TOTAL DENDA (Tampilan Layar) --}}
<div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-4 md:p-5 mb-5 flex justify-between items-center no-print">
    <div>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">Total Pendapatan Denda (Lunas) pada Filter Ini</p>
        <h3 class="text-2xl md:text-3xl font-bold text-emerald-600 dark:text-emerald-400">
            Rp {{ number_format($totalFineAmount, 0, ',', '.') }}
        </h3>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
    <div class="p-4 md:p-5 border-b border-slate-100 dark:border-slate-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-3 no-print">
        <div>
            <h3 class="font-bold text-slate-800 dark:text-white text-sm md:text-base">Riwayat Lengkap</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400">Menampilkan transaksi yang sudah dikembalikan atau ditolak. Bukti pembayaran PDF tersedia pada transaksi denda yang sudah lunas.</p>
        </div>
        <div class="md:hidden text-xs text-amber-600 bg-amber-50 dark:bg-amber-900/30 p-2 rounded-lg w-full">
            PDF dan menu cetak dapat digunakan dari HP. Ketersediaan cetak langsung mengikuti browser/perangkat; Unduh PDF bisa disimpan lalu dicetak kapan saja.
        </div>
    </div>
    
    <div class="overflow-x-auto w-full block">
        <table class="w-full text-sm min-w-[700px]">
            <thead class="bg-slate-50 dark:bg-slate-700 text-slate-500 dark:text-slate-300 uppercase text-xs print:bg-gray-100">
                <tr>
                    <th class="px-5 py-3 text-left">No</th>
                    <th class="px-5 py-3 text-left">Siswa</th>
                    <th class="px-5 py-3 text-left">Buku</th>
                    <th class="px-5 py-3 text-left">Tgl Pinjam</th>
                    <th class="px-5 py-3 text-left">Tgl Kembali</th>
                    <th class="px-5 py-3 text-left">Kondisi</th>
                    <th class="px-5 py-3 text-left">Denda</th>
                    <th class="px-5 py-3 text-left">Status Bayar</th>
                    <th class="px-5 py-3 text-center no-print">Bukti</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $i => $loan)
                <tr class="border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                    <td class="px-5 py-3">{{ $loans->firstItem() + $i }}</td>
                    <td class="px-5 py-3 font-medium dark:text-white whitespace-nowrap">{{ $loan->user->name }}</td>
                    <td class="px-5 py-3 dark:text-slate-300">{{ $loan->book ? $loan->book->title : 'Buku Dihapus' }}</td>
                    <td class="px-5 py-3 dark:text-slate-300 whitespace-nowrap">{{ $loan->loan_date->format('d/m/Y') }}</td>
                    <td class="px-5 py-3 dark:text-slate-300 whitespace-nowrap">
                        {{ $loan->actual_return_date ? $loan->actual_return_date->format('d/m/Y') : '-' }}
                    </td>
                    <td class="px-5 py-3 dark:text-slate-300 whitespace-nowrap">
                        {{ $loan->condition ?? '-' }}
                    </td>
                    <td class="px-5 py-3 whitespace-nowrap">
                        @if($loan->fine > 0)
                            <span class="font-semibold text-rose-600 dark:text-rose-400">
                                Rp {{ number_format($loan->fine, 0, ',', '.') }}
                            </span>
                        @else
                            <span class="text-emerald-600 dark:text-emerald-400 text-xs font-medium">-</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 whitespace-nowrap">
                        @if($loan->fine > 0)
                            @if($loan->payment_status === 'paid')
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-xs">Lunas</span>
                            @elseif($loan->payment_status === 'unpaid')
                                <span class="px-2 py-1 bg-rose-100 text-rose-700 rounded text-xs">Belum Bayar</span>
                            @elseif($loan->payment_status === 'pending_verification')
                                <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded text-xs">Menunggu ACC</span>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-5 py-3 text-center no-print whitespace-nowrap">
                        @if($loan->fine > 0 && $loan->payment_status === 'paid')
                            <a href="{{ route('loans.receipt', $loan) }}" target="_blank" class="inline-flex items-center gap-1 rounded bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-700">
                                Cetak Bukti
                            </a>
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-5 py-8 text-center text-slate-400">Tidak ada riwayat transaksi pada tanggal ini.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-slate-50 dark:bg-slate-700 font-bold print:bg-white">
                <tr>
                    <td colspan="7" class="px-5 py-4 text-right text-slate-800 dark:text-white print:text-black text-sm md:text-base">
                        TOTAL PENDAPATAN DENDA (LUNAS):
                    </td>
                    <td class="px-5 py-4 text-rose-600 dark:text-rose-400 print:text-black text-sm md:text-base">
                        Rp {{ number_format($totalFineAmount, 0, ',', '.') }}
                    </td>
                    <td class="no-print"></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="p-4 border-t border-slate-100 dark:border-slate-700 no-print">
        {{ $loans->links() }}
    </div>
</div>
@endsection