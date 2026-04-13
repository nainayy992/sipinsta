<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/about', [LandingController::class, 'about'])->name('about');


Route::get('/login', [AuthController::class, 'showRoleSelection'])->name('login');

Route::get('/login/admin', [AuthController::class, 'showAdminLogin'])->name('login.admin');
Route::get('/login/siswa', [AuthController::class, 'showSiswaLogin'])->name('login.siswa');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register-admin', [AuthController::class, 'showRegisterAdmin'])->name('register.admin');
Route::post('/register-admin', [AuthController::class, 'registerAdmin'])->name('register.admin.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



Route::middleware(['auth'])->group(function () {

    Route::prefix('admin')->name('admin.')->group(function() {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Kelola Buku
        Route::get('/buku', [App\Http\Controllers\AdminBukuController::class, 'index'])->name('buku.index');
        Route::post('/buku', [App\Http\Controllers\AdminBukuController::class, 'store'])->name('buku.store');
        Route::put('/buku/{id}', [App\Http\Controllers\AdminBukuController::class, 'update'])->name('buku.update');
        Route::delete('/buku/{id}', [App\Http\Controllers\AdminBukuController::class, 'destroy'])->name('buku.destroy');
        
        // Kelola Anggota
        Route::get('/siswa', [AdminController::class, 'indexSiswa'])->name('siswa.index');
        Route::post('/siswa', [AdminController::class, 'storeSiswa'])->name('siswa.store');
        Route::put('/siswa/{id}', [AdminController::class, 'updateSiswa'])->name('siswa.update');
        Route::delete('/siswa/{id}', [AdminController::class, 'destroySiswa'])->name('siswa.destroy');
        
        // Transaksi
        Route::get('/transaksi', [App\Http\Controllers\AdminTransaksiController::class, 'index'])->name('transaksi.index');
    });

});


Route::middleware(['auth'])->group(function () {

    Route::prefix('siswa')->name('siswa.')->group(function() {
        Route::get('/dashboard', [App\Http\Controllers\BukuController::class, 'dashboard'])->name('dashboard');
        Route::get('/cari-buku', [App\Http\Controllers\BukuController::class, 'cari'])->name('buku.cari');
        Route::get('/buku/{id}', [App\Http\Controllers\BukuController::class, 'show'])->name('buku.show');
        Route::post('/buku/{id}/pinjam', [App\Http\Controllers\BukuController::class, 'pinjam'])->name('buku.pinjam');
        Route::get('/peminjaman', [App\Http\Controllers\BukuController::class, 'peminjaman'])->name('peminjaman');
        Route::post('/peminjaman/{id}/kembalikan', [\App\Http\Controllers\BukuController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    });

});