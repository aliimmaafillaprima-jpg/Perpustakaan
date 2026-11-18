<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perpustakaan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2, h3 { text-align: center; margin: 0; }
        h3 { margin-bottom: 5px; }
        .periode { text-align: center; margin-bottom: 20px; font-style: italic; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer-total { font-weight: bold; background-color: #e6e6e6; }
    </style>
</head>
<body>

    <h2>LAPORAN PERPUSTAKAAN LENDORA</h2>
    <h3>SMK Negeri 5 Surakarta</h3>
    
    {{-- MENAMPILKAN PERIODE TANGGAL --}}
    @if(request('start_date') && request('end_date'))
        <div class="periode">Periode: {{ \Carbon\Carbon::parse(request('start_date'))->format('d F Y') }} s/d {{ \Carbon\Carbon::parse(request('end_date'))->format('d F Y') }}</div>
    @else
        <div class="periode">Periode: Semua Data</div>
    @endif
    <hr>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Siswa</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Kondisi</th>
                <th>Denda</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $i => $loan)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $loan->user->name }}</td>
                <td>{{ $loan->book ? $loan->book->title : 'Buku Dihapus' }}</td>
                <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                <td>{{ $loan->actual_return_date ? $loan->actual_return_date->format('d/m/Y') : '-' }}</td>
                <td>{{ $loan->condition ?? '-' }}</td>
                <td>Rp {{ number_format($loan->fine, 0, ',', '.') }}</td>
                <td>{{ $loan->payment_status === 'paid' ? 'Lunas' : ($loan->payment_status === 'unpaid' ? 'Belum Bayar' : 'Menunggu ACC') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Belum ada data transaksi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="footer-total">
                <td colspan="6" class="text-right">TOTAL PENDAPATAN DENDA (LUNAS):</td>
                <td colspan="2">Rp {{ number_format($totalFineAmount, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <p style="margin-top: 30px; text-align: right;">Dicetak pada: {{ $printedAt->translatedFormat('d F Y H:i') }} WIB</p>

</body>
</html>