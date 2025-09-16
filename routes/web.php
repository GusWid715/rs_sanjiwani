<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\User\PesananController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Gizi\DashboardController as GiziDashboardController;
use App\Http\Controllers\Gizi\LaporanController;
use App\Http\Controllers\Gizi\LogController;
use App\Http\Controllers\User\UserController as UserController;
use App\Http\Controllers\Gizi\SetController;
use App\Http\Controllers\User\PesananController as UserPesananController;
// (optional) manager controllers if nanti dipakai
use App\Http\Controllers\Manager\PesananController as ManagerPesananController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Perbaikan routing: konsisten prefix/name/middleware, tambah route 'home'
| untuk menghindari RouteNotFoundException('home').
|
*/

// Halaman welcome (public)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Auth routes: (pastikan package auth yang kamu gunakan menyediakan Auth::routes())
// Jika kamu memakai Laravel Breeze/Fortify, gunakan routing sesuai paket tersebut.
Auth::routes();

// Route 'home' — dipanggil oleh banyak template / scaffolding
// Jika user sudah login, redirect berdasarkan role; jika belum, arahkan ke welcome
Route::get('/home', function () {
    $user = Auth::user();

    if ($user) {
        $map = [
            'admin'   => '/admin',
            'manager' => '/manager',
            'pasien'  => '/user/dashboard',
        ];
        return redirect($map[$user->role] ?? '/');
    }

    return redirect()->route('welcome');
})->name('home');


// ----------------------
// USER (pasien) routes
// ----------------------
Route::middleware(['auth','isUser'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
    Route::get('/user/pesanan/create/{menuId?}', [PesananController::class, 'create'])
    ->name('user.pesanan.create');
    Route::resource('pesanan', PesananController::class);
});


// ================= ADMIN ROUTES =================
Route::prefix('admin')->name('admin.')->middleware(['auth','isAdmin'])->group(function () {
    // dashboard admin
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');

    // resource controller untuk manajemen user (CRUD)
    Route::resource('users', AdminController::class);

    // resource controller untuk manajemen menu (CRUD) - buat controller nanti jika belum ada
});


// ----------------------
// MANAGER routes (opsional)
// ----------------------
// Jika ingin aktifkan manager, pastikan middleware isManager terdaftar dan controllers ada.

Route::prefix('gizi')->name('gizi.')->middleware(['auth','isGizi'])->group(function () {
    Route::get('/', [GiziDashboardController::class, 'dashboard'])->name('dashboard');

    // (opsional) link ke fitur yang nanti Anda buat
    Route::get('/pesanan', [\App\Http\Controllers\Gizi\PesananController::class, 'index'])->name('pesanan.index');
    Route::post('/pesanan/{pesanan}/approve', [\App\Http\Controllers\Gizi\PesananController::class, 'approve'])->name('pesanan.approve');
    Route::resource('sets', SetController::class);
    Route::resource('menus', \App\Http\Controllers\Gizi\MenuController::class);
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
    Route::get('logs', [LogController::class, 'index'])->name('logs.index');
});