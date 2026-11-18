<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with('book')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('user.loans', compact('loans'));
    }

    public function create(Book $book)
    {
        if ($book->stock <= 0) {
            return back()->with('error', 'Stok buku habis!');
        }

        $exists = Loan::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($exists) {
            return redirect()->route('user.loans.index')
                ->with('error', 'Anda masih memiliki peminjaman aktif untuk buku ini!');
        }

        return view('user.loan-create', compact('book'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'loan_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after:loan_date',
            'notes' => 'nullable|string|max:500',
        ]);

        $loanDate = Carbon::parse($validated['loan_date']);
        $returnDate = Carbon::parse($validated['return_date']);
        $diffDays = $loanDate->diffInDays($returnDate);

        if ($diffDays > 14) {
            return back()->withErrors(['return_date' => "Maksimal peminjaman 14 hari. Anda memilih {$diffDays} hari."])->withInput();
        }

        if ($diffDays < 1) {
            return back()->withErrors(['return_date' => 'Minimal peminjaman 1 hari.'])->withInput();
        }

        $book = Book::findOrFail($validated['book_id']);

        if ($book->stock <= 0) {
            return back()->with('error', 'Stok buku habis!');
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        Loan::create($validated);

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => '📥 Pengajuan Peminjaman Baru',
                'message' => auth()->user()->name . ' mengajukan peminjaman buku "' . $book->title . '".',
                'type' => 'info',
                'url' => route('admin.loans.index'),
            ]);
        }

        return redirect()->route('user.loans.index')
            ->with('success', 'Pengajuan peminjaman berhasil! Menunggu verifikasi admin.');
    }

    public function payFine(Request $request, Loan $loan)
    {
        if ($loan->user_id !== auth()->id()) {
            abort(403);
        }

        abort_unless($loan->status === 'returned' && $loan->payment_status === 'unpaid' && (float) $loan->fine > 0, 404);

        $request->validate([
            'payment_method' => 'required|in:Tunai,Transfer BRI,QRIS',
            'payment_proof' => 'required_if:payment_method,QRIS|nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $proofPath = $loan->payment_proof;

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $proofPath = 'proofs/' . time() . '_' . $file->getClientOriginalName();
            $file->storeAs('proofs', basename($proofPath), 'public');
        }

        $loan->update([
            'payment_status' => 'pending_verification',
            'payment_method' => $request->payment_method,
            'payment_proof' => $proofPath,
        ]);

        // NOTIFIKASI KE ADMIN
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Konfirmasi Pembayaran Denda',
                'message' => auth()->user()->name . ' mengirim bukti pembayaran untuk buku "' . $loan->book->title . '".',
                'type' => 'info',
                'url' => route('admin.loans.index'),
            ]);
        }

        return back()->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu konfirmasi admin.');
    }
}