<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Ambil data peminjaman yang sedang aktif
        $activeLoan = Loan::with('book')
            ->where('user_id', $userId)
            ->where('status', 'approved')
            ->latest()
            ->first();

        // 2. Ambil riwayat peminjaman terakhir (5 data)
        $recentLoans = Loan::with('book')
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        // 3. Ambil rekomendasi buku acak yang stoknya masih ada
        $recommended = Book::where('stock', '>', 0)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // 4. Hitung Statistik untuk Kartu di Dashboard
        $totalBorrowed = Loan::where('user_id', $userId)->count();
        $totalActive = Loan::where('user_id', $userId)->where('status', 'approved')->count();
        $totalFine = Loan::where('user_id', $userId)->where('payment_status', 'unpaid')->sum('fine');

        // 5. Kirim semua variabel ke halaman view
        return view('user.dashboard', compact(
            'activeLoan', 
            'recentLoans', 
            'recommended', 
            'totalBorrowed', 
            'totalActive', 
            'totalFine'
        ));
    }
}