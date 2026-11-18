<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();
        $totalStudents = User::where('role', 'siswa')->count();
        $totalBorrowed = Loan::where('status', 'approved')->count();
        $totalLate = Loan::where('status', 'approved')->where('return_date', '<', now())->count();
        $totalUnpaidFine = Loan::where('payment_status', 'unpaid')->sum('fine');

        $recentLoans = Loan::with(['user', 'book'])->latest()->take(5)->get();

        // CHART: Rekap Mingguan (4 Minggu Terakhir)
        $chartLabels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
        $chartData = [];
        
        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
            $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();
            
            $chartData[] = Loan::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
        }

        $topBooks = Book::withCount('loans')->orderByDesc('loans_count')->take(3)->get();
        $lowStockBooks = Book::where('stock', '<=', 3)->orderBy('stock')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalBooks', 'totalStudents', 'totalBorrowed', 'totalLate',
            'totalUnpaidFine', 'recentLoans', 'chartLabels', 'chartData',
            'topBooks', 'lowStockBooks'
        ));
    }
}