<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pembayaran Denda</title>
    <style>
        body { font-family: "DejaVu Sans", sans-serif; color: #172554; font-size: 11px; }
        .receipt { border: 2px solid #1e3a8a; padding: 22px; }
        .header { text-align: center; border-bottom: 2px solid #1e3a8a; padding-bottom: 12px; margin-bottom: 16px; }
        .logo { width: 68px; height: 68px; margin: 0 auto; object-fit: contain; }
        h1 { margin: 7px 0 0; font-size: 17px; letter-spacing: 1px; }
        .school { margin: 4px 0 0; color: #475569; font-size: 10px; }
        .transaction { background: #eff6ff; border: 1px solid #bfdbfe; padding: 10px 12px; margin-bottom: 14px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 6px 4px; vertical-align: top; }
        td:first-child { width: 38%; color: #64748b; }
        .amount { font-size: 16px; font-weight: bold; color: #047857; }
        .paid { margin-top: 16px; padding: 9px; text-align: center; border: 1px solid #059669; background: #ecfdf5; color: #047857; font-weight: bold; letter-spacing: .5px; }
        .note { margin-top: 16px; color: #64748b; font-size: 9px; text-align: center; }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <img class="logo" src="{{ $logoData }}" alt="Logo SMK Negeri 5 Surakarta">
            <h1>BUKTI PEMBAYARAN DENDA</h1>
            <p class="school">LENDORA — Perpustakaan SMK Negeri 5 Surakarta</p>
        </div>

        <div class="transaction">
            <table>
                <tr><td>Kode transaksi</td><td>TRX-{{ str_pad($loan->id, 5, '0', STR_PAD_LEFT) }}</td></tr>
                <tr><td>Tanggal konfirmasi</td><td>{{ $loan->paid_at ? $loan->paid_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') . ' WIB' : '-' }}</td></tr>
            </table>
        </div>

        <table>
            <tr><td>Nama siswa</td><td>{{ $loan->user?->name ?? 'Siswa' }}</td></tr>
            <tr><td>Buku</td><td>{{ $loan->book?->title ?? 'Buku dihapus' }}</td></tr>
            <tr><td>Tanggal pinjam</td><td>{{ $loan->loan_date->format('d/m/Y') }}</td></tr>
            <tr><td>Tanggal kembali</td><td>{{ $loan->actual_return_date?->format('d/m/Y') ?? '-' }}</td></tr>
            <tr><td>Metode pembayaran</td><td>{{ $loan->payment_method ?: '-' }}</td></tr>
            <tr><td>Nominal dibayar</td><td class="amount">Rp {{ number_format((float) $loan->fine, 0, ',', '.') }}</td></tr>
        </table>

        <div class="paid">PEMBAYARAN TELAH DIKONFIRMASI LUNAS</div>
        <p class="note">Bukti ini dibuat oleh sistem LENDORA setelah pembayaran dikonfirmasi oleh admin.</p>
    </div>
</body>
</html>
