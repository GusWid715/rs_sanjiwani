<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

// import semua controller untuk manager
use App\Http\Controllers\Gizi\DashboardController;
use App\Http\Controllers\Gizi\KategoriMenuController;
use App\Http\Controllers\Gizi\MenuController;
use App\Http\Controllers\Gizi\PaketMakananController;
use App\Http\Controllers\Gizi\PesananController;
use App\Http\Controllers\Gizi\LogController;
use App\Http\Controllers\Gizi\LaporanController;


// rute halaman utama
Route::get('/', function () {
    return view('welcome');
});

// rute untuk autentikasi (login, register, logout, dll.)
Auth::routes();

// rute default setelah login
Route::get('/home', [HomeController::class, 'index'])->name('home');



// GRUP ROUTE UNTUK MANAGER
Route::prefix('manager')->name('manager.')->middleware(['auth', 'isManager'])->group(function () {

    // rute untuk dashboard manager
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // rute CRUD untuk Kategori Menu
    Route::resource('kategori', KategoriMenuController::class);
        // route CRUD dasar
    Route::resource('kategori-menu', KategoriMenuController::class);

    // rute CRUD untuk Menu Individual
    Route::resource('menu', MenuController::class);
    
    // rute CRUD untuk Paket Makanan
    Route::resource('paket', PaketMakananController::class);
    // route CRUD dasar
    Route::resource('paket-makanan', PaketMakananController::class);
    // route untuk menambahkan menu ke paket
    Route::post('/paket-makanan/{paketMakanan}/attach', [PaketMakananController::class, 'attachMenu'])->name('paket-makanan.attachMenu');
    // route untuk menghapus menu dari paket
    Route::post('/paket-makanan/{paketMakanan}/detach', [PaketMakananController::class, 'detachMenu'])->name('paket-makanan.detachMenu');
    
    // rute untuk manajemen pesanan
    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
    // rute untuk aksi memproses pesanan
    Route::post('/pesanan/{pesanan}/proses', [PesananController::class, 'process'])->name('pesanan.process');
    // rute untuk aksi menyelesaikan pesanan
    Route::post('/pesanan/{pesanan}/selesai', [PesananController::class, 'complete'])->name('pesanan.complete');
    // rute untuk aksi membatalkan pesanan
    Route::post('/pesanan/{pesanan}/batal', [PesananController::class, 'cancel'])->name('pesanan.cancel');
    
    
    // rute untuk laporan
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

    // rute untuk log aktivitas
    Route::get('logs', [LogController::class, 'index'])->name('logs.index');

});
