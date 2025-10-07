<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacilityReportController;
use App\Http\Controllers\ReportCommentController;
use App\Http\Controllers\PageController; // REVISI: Backslash yang benar

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama untuk tamu (yang belum login)
Route::get('/', function () {
    return view('welcome');
});

// Grup route yang HANYA BISA DIAKSES SETELAH LOGIN
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Route untuk dashboard pengguna
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route untuk semua proses CRUD Laporan Fasilitas
    Route::resource('reports', FacilityReportController::class);

    // Route untuk menyimpan komentar
    Route::post('reports/{report}/comments', [ReportCommentController::class, 'store'])->name('comments.store');

    // Route untuk halaman "Tentang Layanan"
    Route::get('/tentang-layanan', [PageController::class, 'about'])->name('pages.about');
    
});

// Ini adalah route untuk login, registrasi, dll. yang dibuat oleh Breeze
require __DIR__.'/auth.php';

