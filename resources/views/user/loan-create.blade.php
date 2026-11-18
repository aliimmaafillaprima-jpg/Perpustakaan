@extends('layouts.app')

@section('title', 'Ajukan Peminjaman')
@section('page-title', 'Ajukan Peminjaman')

@section('content')
<div class="max-w-2xl w-full">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-4 md:p-6">
        <h3 class="text-lg md:text-xl font-bold mb-1 text-slate-800 dark:text-white">📝 Form Peminjaman Buku</h3>
        <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mb-5">Maksimal peminjaman 14 hari.</p>

        <div class="bg-indigo-50 dark:bg-indigo-900/30 rounded-lg p-3 md:p-4 mb-5 flex gap-4 items-center">
            <img src="{{ $book->cover_url }}" class="w-16 h-20 object-cover rounded shadow shrink-0" alt="">
            <div class="min-w-0">
                <p class="font-bold text-slate-800 dark:text-white truncate">{{ $book->title }}</p>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 truncate">{{ $book->author }}</p>
                <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">Stok: {{ $book->stock }} tersedia</p>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-4 px-4 py-3 bg-rose-100 dark:bg-rose-900/30 border-l-4 border-rose-500 text-rose-700 dark:text-rose-400 rounded text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('user.loans.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="book_id" value="{{ $book->id }}">

            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Nama Peminjam</label>
                <input type="text" value="{{ auth()->user()->name }}" disabled
                    class="w-full px-4 py-2.5 text-sm border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 rounded-lg text-slate-500 dark:text-slate-300">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Tanggal Pinjam *</label>
                    <input type="date" name="loan_date" id="loanDate"
                        value="{{ old('loan_date', now()->format('Y-m-d')) }}"
                        min="{{ now()->format('Y-m-d') }}" required
                        onchange="updateReturnDate()"
                        class="w-full max-w-full px-4 py-2.5 text-sm border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Tanggal Kembali *</label>
                    <input type="date" name="return_date" id="returnDate"
                        value="{{ old('return_date', now()->addDays(7)->format('Y-m-d')) }}" required
                        class="w-full max-w-full px-4 py-2.5 text-sm border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    <p class="text-[10px] md:text-xs text-amber-600 dark:text-amber-400 mt-1">Maksimal 14 hari.</p>
                </div>
            </div>

            <div class="bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 rounded-lg p-3 text-xs md:text-sm">
                <p class="text-amber-700 dark:text-amber-400">
                    💰 <strong>Info Denda:</strong> Keterlambatan dikenakan denda Rp5.000/hari.
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Catatan (opsional)</label>
                <textarea name="notes" rows="3" placeholder="Contoh: Untuk tugas Bahasa Indonesia"
                    class="w-full px-4 py-2.5 text-sm border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('notes') }}</textarea>
            </div>

            <div class="flex flex-col md:flex-row gap-3 pt-2">
                <button class="w-full md:w-auto bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-2.5 rounded-lg shadow transition">
                    Ajukan Peminjaman
                </button>
                <a href="{{ route('public.catalog.show', $book) }}"
                   class="w-full md:w-auto text-center bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-white font-medium px-6 py-2.5 rounded-lg transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateReturnDate() {
    const loanDate = document.getElementById('loanDate').value;
    if (!loanDate) return;
    const d = new Date(loanDate);
    d.setDate(d.getDate() + 7);
    document.getElementById('returnDate').value = d.toISOString().split('T')[0];

    const max = new Date(loanDate);
    max.setDate(max.getDate() + 14);
    document.getElementById('returnDate').max = max.toISOString().split('T')[0];
}

document.getElementById('returnDate').addEventListener('change', function() {
    const loanDate = new Date(document.getElementById('loanDate').value);
    const returnDate = new Date(this.value);
    const diff = Math.round((returnDate - loanDate) / (1000 * 60 * 60 * 24));
    if (diff > 14) { alert('❌ Maksimal 14 hari!'); this.value = ''; }
    else if (diff < 1) { alert('❌ Minimal 1 hari.'); this.value = ''; }
});

updateReturnDate();
</script>
@endpush