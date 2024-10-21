<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\UKMController;

// Routing untuk login
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Routing untuk Mahasiswa berdasarkan NIM (dapat diakses tanpa login)
Route::get('/mahasiswa/{nim}', [MahasiswaController::class, 'show'])->name('mahasiswa.show');


// Routing untuk dashboard (hanya bisa diakses setelah login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Routing untuk Mahasiswa
    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
    Route::get('/mahasiswa/create', [MahasiswaController::class, 'create'])->name('mahasiswa.create');
    Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
    Route::get('/mahasiswa/{mahasiswa}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
    Route::put('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
    Route::delete('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');

    // Routing untuk Prestasi
    Route::get('/prestasi', [PrestasiController::class, 'index'])->name('prestasi.index');
    Route::get('/prestasi/create/{mahasiswa}', [PrestasiController::class, 'create'])->name('prestasi.create');
    Route::post('/prestasi/{mahasiswa}', [PrestasiController::class, 'store'])->name('prestasi.store');
    Route::get('/prestasi/{prestasi}/edit', [PrestasiController::class, 'edit'])->name('prestasi.edit');
    Route::put('/prestasi/{prestasi}', [PrestasiController::class, 'update'])->name('prestasi.update');
    Route::delete('/prestasi/{prestasi}', [PrestasiController::class, 'destroy'])->name('prestasi.destroy');

    Route::get('/ukm', [UKMController::class, 'index'])->name('ukm.index');
    Route::get('/ukm/create', [UKMController::class, 'create'])->name('ukm.create');
    Route::post('/ukm', [UKMController::class, 'store'])->name('ukm.store');
    Route::get('/ukm/{ukm}', [UKMController::class, 'show'])->name('ukm.show');
    Route::get('/ukm/{ukm}/edit', [UKMController::class, 'edit'])->name('ukm.edit');
    Route::put('/ukm/{ukm}', [UKMController::class, 'update'])->name('ukm.update');
    Route::delete('/ukm/{ukm}', [UKMController::class, 'destroy'])->name('ukm.destroy');
    Route::get('/search-mahasiswa', [UKMController::class, 'searchMahasiswa'])->name('search.mahasiswa');

});

// Routing untuk admin (hanya bisa diakses oleh admin)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('users', UserController::class);
});

// Fallback route to handle non-existing routes
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
