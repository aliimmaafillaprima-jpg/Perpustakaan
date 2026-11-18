<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class LoanController extends Controller
{
    public function index()
    {
        $pending = Loan::with(['user', 'book'])->where('status', 'pending')->latest()->get();
        $active = Loan::with(['user', 'book'])->where('status', 'approved')->latest()->get();
        
        $unpaid = Loan::with(['user', 'book'])
            ->where('status', 'returned')
            ->where('payment_status', 'unpaid')
            ->latest()
            ->get();
            
        $waiting_verification = Loan::with(['user', 'book'])
            ->where('payment_status', 'pending_verification')
            ->latest()
            ->get();

        return view('admin.loans.index', compact('pending', 'active', 'unpaid', 'waiting_verification'));
    }

    public function approve(Loan $loan)
    {
        if (!$loan->book || $loan->book->stock <= 0) {
            return back()->with('error', 'Stok buku habis atau buku tidak ditemukan!');
        }

        $loan->update(['status' => 'approved']);
        $loan->book->decrement('stock');

        Notification::create([
            'user_id' => $loan->user_id,
            'title' => 'Peminjaman Disetujui',
            'message' => 'Peminjaman buku "' . $loan->book->title . '" telah disetujui. Silakan ambil di perpustakaan dengan Kode TRX-'.str_pad($loan->id, 5, '0', STR_PAD_LEFT),
            'type' => 'success',
            'url' => route('user.loans.index'),
        ]);

        return back()->with('success', 'Peminjaman disetujui! Stok buku berkurang otomatis.');
    }

    public function reject(Request $request, Loan $loan)
    {
        $loan->update([
            'status' => 'rejected',
            'notes' => $request->notes ?? 'Ditolak oleh admin.',
        ]);

        Notification::create([
            'user_id' => $loan->user_id,
            'title' => 'Peminjaman Ditolak',
            'message' => 'Peminjaman buku "' . ($loan->book ? $loan->book->title : 'Buku') . '" ditolak. Alasan: ' . $loan->notes,
            'type' => 'error',
            'url' => route('user.loans.index'),
        ]);

        return back()->with('success', 'Peminjaman ditolak.');
    }

    public function returnBook(Request $request, Loan $loan)
    {
        $request->validate([
            'condition' => 'required|string|max:50',
            'damage_note' => 'nullable|string|max:500',
            'damage_proof' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'damage_fine' => 'required_if:condition,Hilang|nullable|numeric|min:0|max:99999999',
        ]);

        $actualReturnDate = Carbon::now();
        $late = $loan->return_date->diffInDays($actualReturnDate, false);
        $lateDays = $late > 0 ? $late : 0;
        $fineLate = $lateDays * 5000;

        $fineCondition = 0;
        switch ($request->condition) {
            case 'Rusak Ringan': $fineCondition = 10000; break;
            case 'Rusak Berat':  $fineCondition = 25000; break;
            case 'Hilang':       $fineCondition = (float) $request->input('damage_fine'); break;
            default:             $fineCondition = 0; break;
        }

        $totalFine = $fineLate + $fineCondition;

        $proofPath = $loan->damage_proof;
        if ($request->hasFile('damage_proof')) {
            $file = $request->file('damage_proof');
            $proofPath = 'damage_proofs/' . time() . '_' . $file->getClientOriginalName();
            $file->storeAs('damage_proofs', basename($proofPath), 'public');
        }

        $loan->update([
            'status' => 'returned',
            'actual_return_date' => $actualReturnDate,
            'condition' => $request->condition,
            'damage_note' => $request->damage_note,
            'damage_proof' => $proofPath,
            'late_days' => $lateDays,
            'fine' => $totalFine,
            'payment_status' => $totalFine > 0 ? 'unpaid' : 'none',
        ]);

        if ($loan->book) {
            $loan->book->increment('stock');
        }

        if ($totalFine > 0) {
            Notification::create([
                'user_id' => $loan->user_id,
                'title' => 'Denda Belum Dibayar',
                'message' => 'Buku "' . ($loan->book ? $loan->book->title : 'Buku') . '" dikembalikan. Anda memiliki denda Rp ' . number_format($totalFine, 0, ',', '.') . '. Alasan: ' . $request->condition . '.',
                'type' => 'warning',
                'url' => route('user.loans.index'),
            ]);
        }

        $msg = 'Buku berhasil dikembalikan!';
        if ($totalFine > 0) {
            $msg .= " Total Denda: Rp " . number_format($totalFine, 0, ',', '.');
        }

        return back()->with('success', $msg);
    }

    public function confirmPayment(Request $request, Loan $loan)
    {
        $loan->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
            'payment_method' => $request->payment_method ?? 'Tunai',
        ]);

        Notification::create([
            'user_id' => $loan->user_id,
            'title' => 'Pembayaran Denda Diterima',
            'message' => 'Pembayaran denda Rp ' . number_format($loan->fine, 0, ',', '.') . ' untuk buku "' . $loan->book->title . '" telah diverifikasi Lunas oleh Admin.',
            'type' => 'success',
            'url' => route('user.loans.index'),
        ]);

        return back()->with('success', 'Pembayaran denda berhasil dikonfirmasi!');
    }

    // FUNGSI HISTORY DENGAN FILTER TANGGAL
    public function history(Request $request)
    {
        $query = Loan::with(['user', 'book'])->whereIn('status', ['returned', 'rejected']);

        // Filter berdasarkan tanggal (jika diisi)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('created_at', '>=', $request->start_date)
                  ->whereDate('created_at', '<=', $request->end_date);
        }

        $loans = $query->latest()->paginate(20)->withQueryString();
        
        // Hitung total denda (dengan filter yang sama)
        $totalFineQuery = Loan::where('payment_status', 'paid');
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $totalFineQuery->whereDate('created_at', '>=', $request->start_date)
                           ->whereDate('created_at', '<=', $request->end_date);
        }
        $totalFineAmount = $totalFineQuery->sum('fine');

        return view('admin.loans.history', compact('loans', 'totalFineAmount'));
    }

    // FUNGSI EXPORT PDF DENGAN FILTER TANGGAL
    public function exportPdf(Request $request)
    {
        $query = Loan::with(['user', 'book'])->whereIn('status', ['returned', 'rejected']);

        // Filter berdasarkan tanggal (jika diisi)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('created_at', '>=', $request->start_date)
                  ->whereDate('created_at', '<=', $request->end_date);
        }

        $loans = $query->latest()->get();
            
        $totalFineQuery = Loan::where('payment_status', 'paid');
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $totalFineQuery->whereDate('created_at', '>=', $request->start_date)
                           ->whereDate('created_at', '<=', $request->end_date);
        }
        $totalFineAmount = $totalFineQuery->sum('fine');

        $printedAt = now('Asia/Jakarta');
        $pdf = Pdf::loadView('admin.loans.history_pdf', compact('loans', 'totalFineAmount', 'request', 'printedAt'));
        $pdf->setPaper('A4', 'landscape'); 
        
        return $pdf->download('Laporan_Perpustakaan_LENDORA_' . $printedAt->format('Y-m-d_H-i-s') . '.pdf');
    }
}