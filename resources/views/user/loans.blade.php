@extends('layouts.app')

@section('title', 'Peminjaman Saya')
@section('page-title', 'Peminjaman Saya')

@section('content')
<div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden w-full">
    <div class="p-4 md:p-5 border-b border-slate-100 dark:border-slate-700">
        <h3 class="font-bold text-slate-800 dark:text-white text-sm md:text-base"> Riwayat Peminjaman Saya</h3>
    </div>
    {{-- Tabel dibungkus overflow-x-auto agar bisa digeser di HP --}}
    <div class="overflow-x-auto w-full">
        <table class="w-full text-sm min-w-[600px]">
            <thead class="bg-slate-50 dark:bg-slate-700 text-slate-500 dark:text-slate-300 uppercase text-xs">
                <tr>
                    <th class="px-4 md:px-5 py-3 text-left">Buku</th>
                    <th class="px-4 md:px-5 py-3 text-left">Tgl Pinjam</th>
                    <th class="px-4 md:px-5 py-3 text-left">Status</th>
                    <th class="px-4 md:px-5 py-3 text-left">Denda</th>
                    <th class="px-4 md:px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                <tr class="border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                    <td class="px-4 md:px-5 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $loan->book->cover_url }}" class="w-8 h-11 object-cover rounded shrink-0" alt="">
                            <div>
                                <span class="font-medium text-slate-800 dark:text-white block max-w-[150px] truncate">{{ $loan->book->title }}</span>
                                <span class="text-xs text-slate-500">Kondisi: {{ $loan->condition ?? '-' }}</span>
                                
                                {{-- TOMBOL LIHAT BUKTI KERUSAKAN --}}
                                @if($loan->damage_proof)
                                    <button onclick="showDamageModal('{{ asset('storage/' . $loan->damage_proof) }}', '{{ addslashes($loan->damage_note ?? 'Tidak ada keterangan tambahan.') }}')" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline block mt-1 font-medium">
                                        🔍 Lihat Alasan Denda
                                    </button>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-4 md:px-5 py-3 text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $loan->loan_date->format('d/m/Y') }}</td>
                    <td class="px-4 md:px-5 py-3 whitespace-nowrap">
                        @if($loan->status === 'approved')
                            <div class="text-xs font-bold text-orange-600 dark:text-orange-400">Kode Ambil:</div>
                            <div class="font-mono text-sm bg-orange-100 dark:bg-orange-900/50 text-orange-700 dark:text-orange-300 px-2 py-1 rounded inline-block mt-1">
                                TRX-{{ str_pad($loan->id, 5, '0', STR_PAD_LEFT) }}
                            </div>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $loan->statusColor() }}">
                                {{ $loan->statusLabel() }}
                            </span>
                        @endif
                    </td>
                    <td class="px-4 md:px-5 py-3 whitespace-nowrap">
                        @if($loan->fine > 0)
                            <div class="font-bold text-rose-600 dark:text-rose-400">
                                Rp {{ number_format($loan->fine, 0, ',', '.') }}
                            </div>
                            @if($loan->payment_status === 'paid')
                                <span class="text-[10px] bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded">✅ Lunas</span>
                            @elseif($loan->payment_status === 'pending_verification')
                                <span class="text-[10px] bg-amber-100 text-amber-700 px-2 py-0.5 rounded">⏳ Menunggu ACC</span>
                            @elseif($loan->payment_status === 'unpaid')
                                <span class="text-[10px] bg-rose-100 text-rose-700 px-2 py-0.5 rounded animate-pulse">💸 Belum Bayar</span>
                            @endif
                        @else
                            <span class="text-slate-400 dark:text-slate-500">-</span>
                        @endif
                    </td>
                    <td class="px-4 md:px-5 py-3 text-center whitespace-nowrap">
                        @if($loan->payment_status === 'unpaid' && $loan->status === 'returned')
                            <button onclick="openPayModal({{ $loan->id }}, {{ $loan->fine }}, @js($loan->book?->title ?? 'Buku'))" class="px-3 py-1.5 bg-indigo-500 hover:bg-indigo-600 text-white rounded text-xs shadow transition">
                                 Bayar Denda
                            </button>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-12 text-center text-slate-400 dark:text-slate-500">
                    <div class="text-5xl mb-3"></div>
                    <p class="mb-3">Belum ada peminjaman.</p>
                    <a href="{{ route('public.catalog') }}" class="inline-block bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-lg text-sm">
                        Jelajahi Katalog
                    </a>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($loans->hasPages())
        <div class="p-4 border-t border-slate-100 dark:border-slate-700">{{ $loans->links() }}</div>
    @endif
</div>

{{-- MODAL LIHAT KERUSAKAN --}}
<div id="damageModal" class="hidden fixed inset-0 bg-black/80 z-[70] flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl max-w-sm w-full overflow-hidden">
        <div class="p-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
            <h3 class="font-bold text-slate-800 dark:text-white">Bukti Kerusakan</h3>
            <button onclick="document.getElementById('damageModal').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 text-2xl leading-none">&times;</button>
        </div>
        <div class="p-4">
            <img id="damageImage" src="" class="w-full rounded-lg mb-3 shadow-sm border" alt="Bukti Kerusakan">
            <p class="text-xs text-slate-500 dark:text-slate-400 italic">Keterangan: <span id="damageText" class="text-slate-700 dark:text-slate-300 font-medium"></span></p>
        </div>
        <div class="p-4 bg-slate-50 dark:bg-slate-700/50 text-right">
            <button onclick="document.getElementById('damageModal').classList.add('hidden')" class="px-4 py-2 bg-slate-200 dark:bg-slate-600 rounded-lg text-sm font-medium">Tutup</button>
        </div>
    </div>
</div>

{{-- MODAL BAYAR DENDA --}}
<div id="payModal" class="hidden fixed inset-0 bg-black/50 z-[60] overflow-y-auto flex items-start md:items-center justify-center p-3 md:p-4">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl max-w-md w-full p-5 md:p-6 my-8">
        <h3 class="text-lg md:text-xl font-bold mb-4 text-slate-800 dark:text-white">Bayar Denda</h3>
        <form id="payForm" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="bg-rose-50 dark:bg-rose-900/30 p-3 rounded-lg mb-4 text-sm md:text-base">
                <p class="text-rose-600 dark:text-rose-400 mb-1 truncate">Denda Buku: <strong id="payBookTitle"></strong></p>
                <p class="text-rose-600 dark:text-rose-400 mb-1">Total Tagihan:</p>
                <p id="payAmount" class="text-xl md:text-2xl font-bold text-rose-700 dark:text-rose-400"></p>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Metode Pembayaran *</label>
                <select name="payment_method" id="payMethod" onchange="toggleProofInput()" required class="w-full px-4 py-2.5 text-sm border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg">
                    <option value="Tunai"> Tunai ke Perpustakaan</option>
                    <option value="Transfer BRI"> Transfer Bank BRI</option>
                    <option value="QRIS"> QR DANA</option>
                </select>
            </div>

            <div id="infoBRI" class="hidden bg-blue-50 dark:bg-blue-900/30 p-3 rounded-lg border border-blue-200 text-sm mb-4 text-blue-700 dark:text-blue-300">
                <p class="font-bold mb-1 text-base"> Rekening Tujuan:</p>
                <p class="font-bold text-base">Bank BRI</p>
                <p class="text-lg">No. Rek: <strong>2153 0100 3918 532</strong></p>
                <p>a.n. Aliimma Afilla Prima</p>
                <p class="mt-2 text-xs italic">*Pastikan siswa menunjukkan bukti transfer.</p>
            </div>

            <div id="infoQRIS" class="hidden bg-slate-50 dark:bg-slate-700 p-3 rounded-lg text-center border mb-4">
                <p id="qrisTitle" class="font-bold text-sm mb-2 text-slate-700 dark:text-slate-300">Scan QR DANA</p>
                <img id="qrisImage" src="{{ asset('images/qris_dn.jpeg') }}" alt="QR DANA" class="mx-auto rounded-lg shadow-sm border border-slate-200 max-h-[220px] w-auto object-contain bg-white">
                <p id="qrisHelp" class="text-xs text-slate-500 mt-2"></p>
            </div>

            <div id="uploadProof" class="mb-4 hidden">
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Unggah Bukti Pembayaran</label>
                <input type="file" name="payment_proof" id="paymentProofInput" accept="image/jpeg,image/png" class="w-full px-4 py-2 text-sm border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg">
            </div>

            <div class="flex gap-2 md:gap-3 mt-5">
                <button type="submit" id="paymentSubmitLabel" class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold py-2.5 rounded-lg">Kirim</button>
                <button type="button" onclick="document.getElementById('payModal').classList.add('hidden')" class="flex-1 bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-white text-sm py-2.5 rounded-lg">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const payRouteTemplate = @json(route('user.loans.pay', ['loan' => 'LOAN_ID']));
let payLoanId = null;
let payFineAmount = 0;

// Fungsi Modal Kerusakan
function showDamageModal(imageUrl, note) {
    document.getElementById('damageImage').src = imageUrl;
    document.getElementById('damageText').textContent = note;
    document.getElementById('damageModal').classList.remove('hidden');
}

// Fungsi Modal Pembayaran
function openPayModal(id, amount, bookTitle) {
    payLoanId = id;
    payFineAmount = amount;
    document.getElementById('payBookTitle').textContent = bookTitle;
    document.getElementById('payAmount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    document.getElementById('payMethod').value = 'Tunai';
    document.getElementById('paymentProofInput').value = '';
    document.getElementById('payModal').classList.remove('hidden');
    toggleProofInput();
}

function toggleProofInput() {
    const method = document.getElementById('payMethod').value;
    const infoBRI = document.getElementById('infoBRI');
    const infoQRIS = document.getElementById('infoQRIS');
    const uploadProof = document.getElementById('uploadProof');
    const proofInput = document.getElementById('paymentProofInput');
    const qrisHelp = document.getElementById('qrisHelp');
    const payForm = document.getElementById('payForm');

    infoBRI.classList.add('hidden');
    infoQRIS.classList.add('hidden');
    uploadProof.classList.add('hidden');
    proofInput.required = false;
    payForm.action = payRouteTemplate.replace('LOAN_ID', payLoanId);

    if (method === 'Transfer BRI') {
        infoBRI.classList.remove('hidden');
        uploadProof.classList.remove('hidden');
    } else if (method === 'QRIS') {
        infoQRIS.classList.remove('hidden');
        qrisHelp.textContent = 'Buka DANA untuk memindai QR ini, lalu masukkan nominal Rp ' + new Intl.NumberFormat('id-ID').format(payFineAmount) + ' secara manual. Setelah membayar, unggah bukti pembayaran.';
        uploadProof.classList.remove('hidden');
        proofInput.required = true;
    }
}
</script>
@endpush