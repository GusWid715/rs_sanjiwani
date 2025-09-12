<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\User\UserController as UserController;
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
Route::prefix('user')->name('user.')->middleware(['auth','isUser'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('user.dashboard');
    });

    // Dashboard pasien
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');

    // Resource routes untuk pesanan pasien: index, create, store, show, destroy, etc.
    Route::resource('pesanan', UserPesananController::class);
});


// ================= ADMIN ROUTES =================
Route::prefix('admin')->name('admin.')->middleware(['auth','isAdmin'])->group(function () {
    // dashboard admin
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');

    // resource controller untuk manajemen user (CRUD)
    Route::resource('users', AdminController::class);

    // resource controller untuk manajemen menu (CRUD) - buat controller nanti jika belum ada
    Route::resource('menus', MenuController::class);
});


// ----------------------
// MANAGER routes (opsional)
// ----------------------
// Jika ingin aktifkan manager, pastikan middleware isManager terdaftar dan controllers ada.
// Route::prefix('manager')->name('manager.')->middleware(['auth','isManager'])->group(function () {
//     Route::get('/', function () {
//         return redirect()->route('manager.dashboard');
//     });
//     Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');
//     Route::resource('pesanan', ManagerPesananController::class)->only(['index','show','update']);
// });
