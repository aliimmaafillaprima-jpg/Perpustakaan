@extends('layouts.app')

@section('title', 'Verifikasi Peminjaman')
@section('page-title', 'Verifikasi Peminjaman')

@section('content')
@if($errors->any())
    <div class="mb-4 px-4 py-3 bg-rose-100 border-l-4 border-rose-500 text-rose-700 rounded">
        {{ $errors->first() }}
    </div>
@endif

{{-- TAB NAVIGASI --}}
<div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 mb-5 overflow-hidden w-full">
    <div class="flex w-full justify-between border-b border-slate-200 dark:border-slate-700">
        <button onclick="switchTab('pending')" id="tab-pending" class="tab-btn flex-1 text-center px-1 md:px-4 py-3 md:py-4 text-[11px] md:text-sm font-medium text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 transition">Menunggu ({{ $pending->count() }})</button>
        <button onclick="switchTab('active')" id="tab-active" class="tab-btn flex-1 text-center px-1 md:px-4 py-3 md:py-4 text-[11px] md:text-sm font-medium text-slate-500 hover:text-slate-700 dark:text-slate-400 border-b-2 border-transparent transition">Dipinjam ({{ $active->count() }})</button>
        <button onclick="switchTab('unpaid')" id="tab-unpaid" class="tab-btn flex-1 text-center px-1 md:px-4 py-3 md:py-4 text-[11px] md:text-sm font-medium text-slate-500 hover:text-slate-700 dark:text-slate-400 border-b-2 border-transparent transition">Denda ({{ $unpaid->count() }})</button>
        <button onclick="switchTab('verifikasi')" id="tab-verifikasi" class="tab-btn flex-1 text-center px-1 md:px-4 py-3 md:py-4 text-[11px] md:text-sm font-medium text-slate-500 hover:text-slate-700 dark:text-slate-400 border-b-2 border-transparent transition">Cek Bukti ({{ $waiting_verification->count() }})</button>
    </div>
</div>

{{-- TAB 1: PENDING --}}
<div id="content-pending" class="tab-content">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden w-full max-w-full">
        <div class="overflow-x-auto w-full block">
            <table class="w-full text-sm min-w-[600px]">
                <thead class="bg-slate-50 dark:bg-slate-700 text-slate-500 dark:text-slate-300 uppercase text-xs">
                    <tr>
                        <th class="px-5 py-3 text-left">Siswa</th>
                        <th class="px-5 py-3 text-left">Buku</th>
                        <th class="px-5 py-3 text-left">Tgl Pinjam</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pending as $loan)
                    <tr class="border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                        <td class="px-5 py-3 font-medium text-slate-800 dark:text-white">{{ $loan->user->name }}</td>
                        <td class="px-5 py-3 text-slate-600 dark:text-slate-300">{{ $loan->book ? $loan->book->title : 'Buku Dihapus' }}</td>
                        <td class="px-5 py-3 text-slate-500 dark:text-slate-400">{{ $loan->loan_date->format('d/m/Y') }}</td>
                        <td class="px-5 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <form action="{{ route('admin.loans.approve', $loan) }}" method="POST">
                                    @csrf
                                    <button class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded text-xs font-medium">Setujui</button>
                                </form>
                                <button onclick="openRejectModal({{ $loan->id }})" class="px-3 py-1.5 bg-rose-500 hover:bg-rose-600 text-white rounded text-xs font-medium">Tolak</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center text-slate-400">Tidak ada pengajuan baru.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- TAB 2: ACTIVE --}}
<div id="content-active" class="tab-content hidden">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden w-full max-w-full">
        <div class="overflow-x-auto w-full block">
            <table class="w-full text-sm min-w-[600px]">
                <thead class="bg-slate-50 dark:bg-slate-700 text-slate-500 dark:text-slate-300 uppercase text-xs">
                    <tr>
                        <th class="px-5 py-3 text-left">Siswa</th>
                        <th class="px-5 py-3 text-left">Buku</th>
                        <th class="px-5 py-3 text-left">Jatuh Tempo</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($active as $loan)
                    <tr class="border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                        <td class="px-5 py-3 font-medium text-slate-800 dark:text-white">{{ $loan->user->name }}</td>
                        <td class="px-5 py-3 text-slate-600 dark:text-slate-300">{{ $loan->book ? $loan->book->title : 'Buku Dihapus' }}</td>
                        <td class="px-5 py-3 text-slate-500 dark:text-slate-400">{{ $loan->return_date->format('d/m/Y') }}</td>
                        <td class="px-5 py-3">
                            @if($loan->isLate())
                                <span class="px-3 py-1 bg-rose-100 dark:bg-rose-900/50 text-rose-700 dark:text-rose-400 rounded-full text-xs font-medium">Terlambat {{ now()->startOfDay()->diffInDays($loan->return_date->startOfDay()) }} hari</span>
                            @else
                                <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400 rounded-full text-xs font-medium">Aktif</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-center">
                            <button onclick="openReturnModal({{ $loan->id }}, '{{ $loan->book ? addslashes($loan->book->title) : 'Buku Dihapus' }}', '{{ addslashes($loan->user->name) }}')" class="px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white rounded text-xs font-medium">Catat Kembali</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center text-slate-400">Tidak ada peminjaman aktif.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- TAB 3: UNPAID --}}
<div id="content-unpaid" class="tab-content hidden">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden w-full max-w-full">
        <div class="overflow-x-auto w-full block">
            <table class="w-full text-sm min-w-[600px]">
                <thead class="bg-slate-50 dark:bg-slate-700 text-slate-500 dark:text-slate-300 uppercase text-xs">
                    <tr>
                        <th class="px-5 py-3 text-left">Siswa</th>
                        <th class="px-5 py-3 text-left">Buku</th>
                        <th class="px-5 py-3 text-left">Denda</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($unpaid as $loan)
                    <tr class="border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                        <td class="px-5 py-3 font-medium text-slate-800 dark:text-white">{{ $loan->user->name }}</td>
                        <td class="px-5 py-3 text-slate-600 dark:text-slate-300">{{ $loan->book ? $loan->book->title : 'Buku Dihapus' }}</td>
                        <td class="px-5 py-3">
                            <span class="font-bold text-rose-600 dark:text-rose-400">Rp {{ number_format($loan->fine, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <button onclick="openPaymentModal({{ $loan->id }}, '{{ addslashes($loan->user->name) }}', '{{ addslashes($loan->book ? $loan->book->title : 'Buku') }}', {{ $loan->fine }})" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded text-xs font-medium">Bayar</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center text-slate-400">Tidak ada denda tertunggak.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- TAB 4: VERIFIKASI BUKTI BAYAR --}}
<div id="content-verifikasi" class="tab-content hidden">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden w-full max-w-full">
        <div class="overflow-x-auto w-full block">
            <table class="w-full text-sm min-w-[600px]">
                <thead class="bg-slate-50 dark:bg-slate-700 text-slate-500 dark:text-slate-300 uppercase text-xs">
                    <tr>
                        <th class="px-5 py-3 text-left">Siswa</th>
                        <th class="px-5 py-3 text-left">Buku</th>
                        <th class="px-5 py-3 text-left">Denda</th>
                        <th class="px-5 py-3 text-left">Metode</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($waiting_verification as $loan)
                    <tr class="border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                        <td class="px-5 py-3 font-medium text-slate-800 dark:text-white">{{ $loan->user->name }}</td>
                        <td class="px-5 py-3 text-slate-600 dark:text-slate-300">{{ $loan->book ? $loan->book->title : 'Buku' }}</td>
                        <td class="px-5 py-3 font-bold text-rose-600 dark:text-rose-400">Rp {{ number_format($loan->fine, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-slate-600 dark:text-slate-300">{{ $loan->payment_method }}</td>
                        <td class="px-5 py-3 text-center">
                            <div class="flex flex-col gap-2 items-center">
                                @if($loan->payment_proof)
                                    <a href="{{ asset('storage/' . $loan->payment_proof) }}" target="_blank" class="text-xs bg-indigo-100 text-indigo-700 hover:bg-indigo-200 px-3 py-1 rounded font-semibold">Lihat Bukti</a>
                                @endif
                                <form action="{{ route('admin.loans.confirmPayment', $loan) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="payment_method" value="{{ $loan->payment_method }}">
                                    <button class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded text-xs font-medium w-full">Terima</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center text-slate-400">Tidak ada antrean verifikasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL RETURN --}}
<div id="returnModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl max-w-md w-full p-6">
        <h3 class="text-xl font-bold mb-4 text-slate-800 dark:text-white">Konfirmasi Pengembalian</h3>
        <form id="returnForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3 bg-slate-50 dark:bg-slate-700 rounded-lg p-3">
                <p class="text-xs text-slate-400 mb-1">Buku yang dikembalikan:</p>
                <p id="returnBook" class="font-semibold text-slate-800 dark:text-white"></p>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Kondisi Buku *</label>
                <select name="condition" id="conditionSelect" onchange="updateFineInfo()" required class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg">
                    <option value="Baik">Baik</option>
                    <option value="Rusak Ringan">Rusak Ringan</option>
                    <option value="Rusak Berat">Rusak Berat</option>
                    <option value="Hilang">Hilang</option>
                </select>
                <p id="fineInfo" class="text-xs text-rose-600 dark:text-rose-400 mt-2 hidden font-medium">Denda kerusakan: <span id="fineAmount">Rp 0</span></p>
                <div id="lostFineField" class="hidden mt-3">
                    <label for="damageFineInput" class="block text-sm font-medium mb-1 text-slate-700 dark:text-white">Nominal denda buku hilang *</label>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-slate-600 dark:text-slate-300">Rp</span>
                        <input type="number" name="damage_fine" id="damageFineInput" min="0" max="99999999" step="1000" inputmode="numeric" placeholder="Masukkan nominal denda"
                            class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg">
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Tentukan nominal sesuai nilai buku oleh admin.</p>
                </div>
            </div>
            <div class="mb-4 p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800">
                <p class="text-xs text-amber-700 dark:text-amber-400 mb-2">Khusus jika Rusak/Hilang, isi detail di bawah:</p>
                <div class="mb-2">
                    <input type="text" name="damage_note" id="damageNoteInput" placeholder="Contoh: Halaman 5 sobek" class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg">
                </div>
                <div>
                    <input type="file" name="damage_proof" id="damageProofInput" accept="image/*" class="w-full text-xs file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 file:cursor-pointer border border-slate-300 dark:border-slate-600 rounded-lg bg-slate-50 dark:bg-slate-700">
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2.5 rounded-lg">Konfirmasi</button>
                <button type="button" onclick="closeModal('returnModal')" class="flex-1 bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-white py-2.5 rounded-lg">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL PAYMENT --}}
<div id="paymentModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl max-w-md w-full p-6">
        <h3 class="text-xl font-bold mb-4 text-slate-800 dark:text-white">Konfirmasi Pembayaran</h3>
        <div class="bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 rounded-lg p-4 mb-4">
            <p class="text-sm text-amber-600 dark:text-amber-400 mb-1">Siswa: <strong id="payStudentName"></strong></p>
            <p class="text-sm text-amber-600 dark:text-amber-400 mb-1">Buku: <strong id="payBookName"></strong></p>
            <p class="text-xs text-amber-600 dark:text-amber-400 mb-1 mt-2">Total Tagihan Denda:</p>
            <p id="paymentAmount" class="text-2xl font-bold text-amber-700 dark:text-amber-400"></p>
        </div>
        <form id="paymentForm" method="POST">
            @csrf
            <input type="hidden" name="payment_method" value="Tunai">
            <div class="bg-slate-50 dark:bg-slate-700 p-3 rounded-lg border border-slate-200 dark:border-slate-600 text-sm text-slate-600 dark:text-slate-300 mb-5">
                Pastikan pembayaran Uang Tunai, Transfer, dan QRIS sudah masuk dan diterima.
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-2.5 rounded-lg">Konfirmasi Lunas</button>
                <button type="button" onclick="closeModal('paymentModal')" class="flex-1 bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-white py-2.5 rounded-lg">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL REJECT --}}
<div id="rejectModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl max-w-md w-full p-6">
        <h3 class="text-xl font-bold mb-4 text-slate-800 dark:text-white">Alasan Penolakan</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="notes" rows="4" required placeholder="Contoh: Stok habis untuk periode ini." class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg mb-4"></textarea>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-rose-500 hover:bg-rose-600 text-white font-semibold py-2.5 rounded-lg">Tolak</button>
                <button type="button" onclick="closeModal('rejectModal')" class="flex-1 bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-white py-2.5 rounded-lg">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(el => {
        el.classList.remove('text-indigo-600', 'dark:text-indigo-400', 'border-indigo-600');
        el.classList.add('text-slate-500', 'dark:text-slate-400', 'border-transparent');
    });
    document.getElementById('content-' + tab).classList.remove('hidden');
    document.getElementById('tab-' + tab).classList.add('text-indigo-600', 'dark:text-indigo-400', 'border-indigo-600');
    document.getElementById('tab-' + tab).classList.remove('text-slate-500', 'dark:text-slate-400', 'border-transparent');
}

function openReturnModal(id, bookTitle, studentName) {
    document.getElementById('returnBook').textContent = bookTitle + ' (oleh ' + studentName + ')';
    document.getElementById('returnForm').action = '/admin/loans/' + id + '/return';
    document.getElementById('returnModal').classList.remove('hidden');
    document.getElementById('conditionSelect').value = 'Baik';
    document.getElementById('damageNoteInput').value = '';
    document.getElementById('damageProofInput').value = '';
    document.getElementById('damageFineInput').value = '';
    updateFineInfo();
}

function updateFineInfo() {
    const condition = document.getElementById('conditionSelect').value;
    const info = document.getElementById('fineInfo');
    const amount = document.getElementById('fineAmount');
    const lostFineField = document.getElementById('lostFineField');
    const damageFineInput = document.getElementById('damageFineInput');
    let fine = 0;
    if (condition === 'Rusak Ringan') fine = 10000;
    else if (condition === 'Rusak Berat') fine = 25000;

    lostFineField.classList.toggle('hidden', condition !== 'Hilang');
    damageFineInput.required = condition === 'Hilang';

    if (fine > 0) {
        amount.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(fine);
        info.classList.remove('hidden');
    } else {
        info.classList.add('hidden');
    }
}

function openPaymentModal(id, studentName, bookTitle, amount) {
    document.getElementById('payStudentName').textContent = studentName;
    document.getElementById('payBookName').textContent = bookTitle;
    document.getElementById('paymentAmount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    document.getElementById('paymentForm').action = '/admin/loans/' + id + '/confirm-payment';
    document.getElementById('paymentModal').classList.remove('hidden');
}

function openRejectModal(id) {
    document.getElementById('rejectForm').action = '/admin/loans/' + id + '/reject';
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}
</script>
@endpush