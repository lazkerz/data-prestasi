<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\UKMController;

// Route untuk login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Arahkan root URL ke login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/{nim}', [MahasiswaController::class, 'show'])
    ->where('nim', '[0-9]+')
    ->name('mahasiswa.show');
// Routing yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    // Routing untuk dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Routing untuk Mahasiswa
    Route::prefix('mahasiswa')->group(function () {
        Route::get('/', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
        Route::get('/create', [MahasiswaController::class, 'create'])->name('mahasiswa.create');
        Route::post('/', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
        Route::get('/{mahasiswa}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
        Route::put('/{mahasiswa}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
        Route::delete('/{mahasiswa}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');
    });

    // Routing untuk Prestasi
    Route::prefix('prestasi')->group(function () {
        Route::get('/', [PrestasiController::class, 'index'])->name('prestasi.index');
        Route::get('/create/{mahasiswa}', [PrestasiController::class, 'create'])->name('prestasi.create');
        Route::post('/{mahasiswa}', [PrestasiController::class, 'store'])->name('prestasi.store');
        Route::get('/{prestasi}/edit', [PrestasiController::class, 'edit'])->name('prestasi.edit');
        Route::put('/{prestasi}', [PrestasiController::class, 'update'])->name('prestasi.update');
        Route::delete('/{prestasi}', [PrestasiController::class, 'destroy'])->name('prestasi.destroy');
    });

    // Routing untuk UKM
    Route::prefix('ukm')->group(function () {
        Route::get('/', [UKMController::class, 'index'])->name('ukm.index');
        Route::get('/create', [UKMController::class, 'create'])->name('ukm.create');
        Route::post('/', [UKMController::class, 'store'])->name('ukm.store');
        Route::get('/{ukm}', [UKMController::class, 'show'])->name('ukm.show');
        Route::get('/{ukm}/edit', [UKMController::class, 'edit'])->name('ukm.edit');
        Route::put('/{ukm}', [UKMController::class, 'update'])->name('ukm.update');
        Route::delete('/{ukm}', [UKMController::class, 'destroy'])->name('ukm.destroy');
    });

    // Routing untuk pencarian Mahasiswa dalam UKM
    Route::get('/search-mahasiswa', [UKMController::class, 'searchMahasiswa'])->name('search.mahasiswa');
});

// Routing untuk admin (hanya bisa diakses oleh admin)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('users', UserController::class);
});

// Fallback route untuk menangani rute yang tidak ada
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
