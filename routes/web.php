<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\LoanController as AdminLoan;
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\CatalogController;
use App\Http\Controllers\User\LoanController as UserLoan;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoanReceiptController;

// ==================== AREA PUBLIK ====================
Route::get('/', function () {
    $topBooks = \App\Models\Book::withCount('loans')->orderByDesc('loans_count')->take(4)->get();
    return view('landing', compact('topBooks'));
})->name('landing');

Route::get('/katalog', [CatalogController::class, 'index'])->name('public.catalog');
Route::get('/katalog/{book}', [CatalogController::class, 'show'])->name('public.catalog.show');

// ==================== AUTENTIKASI SISWA ====================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// ==================== AUTENTIKASI ADMIN ====================
Route::get('/admin/login', function () {
    return view('auth.admin-login');
})->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'adminLogin'])->name('admin.login.post');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// ==================== RUTE AUTH (NOTIFIKASI & PROFIL) ====================
Route::middleware('auth')->group(function () {
    Route::get('/loans/{loan}/receipt', [LoanReceiptController::class, 'show'])->name('loans.receipt');
    Route::get('/notifications/fetch', [NotificationController::class, 'fetch'])->name('notifications.fetch');
    Route::get('/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.delete-avatar');
});

// ==================== AREA ADMIN ====================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    Route::resource('books', BookController::class);
    Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit']);
    Route::resource('students', StudentController::class);

    // RUTE PEMINJAMAN ADMIN
    Route::get('/loans', [AdminLoan::class, 'index'])->name('loans.index');
    Route::get('/loans/history', [AdminLoan::class, 'history'])->name('loans.history');
    Route::get('/loans/history/pdf', [AdminLoan::class, 'exportPdf'])->name('loans.history.pdf'); // RUTE PDF
    Route::post('/loans/{loan}/approve', [AdminLoan::class, 'approve'])->name('loans.approve');
    Route::post('/loans/{loan}/reject', [AdminLoan::class, 'reject'])->name('loans.reject');
    Route::post('/loans/{loan}/return', [AdminLoan::class, 'returnBook'])->name('loans.return');
    Route::post('/loans/{loan}/confirm-payment', [AdminLoan::class, 'confirmPayment'])->name('loans.confirmPayment');

    // RUTE QR CODE BUKU
    Route::get('/books/{book}/qr', [BookController::class, 'showQR'])->name('books.qr');
});

// ==================== AREA SISWA ====================
Route::middleware(['auth', 'role:siswa'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');
    Route::get('/loans', [UserLoan::class, 'index'])->name('loans.index');
    Route::get('/loans/create/{book}', [UserLoan::class, 'create'])->name('loans.create');
    Route::post('/loans', [UserLoan::class, 'store'])->name('loans.store');
    Route::post('/loans/{loan}/pay', [UserLoan::class, 'payFine'])->name('loans.pay');
});