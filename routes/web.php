<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\UKMController;
use App\Http\Controllers\HmpsController;
use App\Http\Controllers\HmpsMemberController;

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


    Route::prefix('ukm')->middleware(['auth'])->group(function () {
        // Basic UKM routes - accessible by both admin and ukm users
        Route::get('/', [UKMController::class, 'index'])->name('ukm.index');

        // Admin only routes
        Route::middleware(['auth'])->group(function () {
            Route::get('/create', [UKMController::class, 'create'])->name('ukm.create');
            Route::post('/', [UKMController::class, 'store'])->name('ukm.store');
            Route::get('/{ukm}/edit', [UKMController::class, 'edit'])->name('ukm.edit');
            Route::put('/{ukm}', [UKMController::class, 'update'])->name('ukm.update');
            Route::delete('/{ukm}', [UKMController::class, 'destroy'])->name('ukm.destroy');
        });

        // Member management routes - accessible by both admin and ukm users

        Route::get('/{ukm}/members', [UKMController::class, 'showMembers'])->name('ukm.members');
        Route::post('/{ukm}/members', [UkmController::class, 'addMembers'])->name('ukm.members.add');
        Route::put('/{ukm}/members/{member}/edit', [UKMController::class, 'updateMember'])->name('ukm.members.update');
        Route::delete('/{ukm}/members/{member}', [UKMController::class, 'removeMember'])->name('ukm.members.remove');
    });


    Route::prefix('hmps')->middleware(['auth'])->group(function () {
        // Basic HMPS routes - accessible by both admin and hmps users
        Route::get('/', [HmpsController::class, 'index'])->name('hmps.index');

        // Admin only routes
            Route::get('/create', [HmpsController::class, 'create'])->name('hmps.create');
            Route::post('/', [HmpsController::class, 'store'])->name('hmps.store');
            Route::get('/{hmps}/edit', [HmpsController::class, 'edit'])->name('hmps.edit');
            Route::put('/{hmps}', [HmpsController::class, 'update'])->name('hmps.update');
            Route::delete('/{hmps}', [HmpsController::class, 'destroy'])->name('hmps.destroy');

            Route::prefix('/{hmps}/members')->group(function () {
                Route::get('/', [HmpsMemberController::class, 'showMembers'])->name('hmps.members');
                Route::post('/add', [HmpsMemberController::class, 'addMembers'])->name('hmps.members.add');
                Route::put('/{memberId}/update', [HmpsMemberController::class, 'updateMember'])->name('hmps.members.update');
                Route::delete('/{memberId}/remove', [HmpsMemberController::class, 'removeMember'])->name('hmps.members.remove');

            });


    });

    Route::get('/hmps/{hmps}/search-mahasiswa', [HmpsMemberController::class, 'searchMahasiswa'])->name('hmps.search.mahasiswa');
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
