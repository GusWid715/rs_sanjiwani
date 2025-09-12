<?php

namespace App\Http\Controllers\Admin; 
// Menentukan namespace agar controller ini termasuk ke folder Admin

use App\Http\Controllers\Controller; 
// Mengimpor base Controller Laravel

use App\Models\Log_aktivitas; 
// Model untuk tabel log aktivitas (mencatat aktivitas user)

use App\Models\User; 
// Model User, untuk mengakses data user di tabel users

use App\Models\menus; 
// Model menus, untuk mengakses data menu (navigasi/menu aplikasi)

class DashboardController extends Controller
{
    // ================= DASHBOARD UTAMA =================
    public function dashboard()
    {
        // Hitung total semua user
        $totalUsers   = User::count();

        // Hitung jumlah user dengan role admin
        $totalAdmins  = User::where('role', 'admin')->count();

        // Hitung jumlah user dengan role manager
        $totalManagers = User::where('role', 'manager')->count();

        // Hitung jumlah user dengan role pasien
        $totalPasien  = User::where('role', 'pasien')->count();

        // Hitung total menu yang ada
        $totalMenus   = menus::count();

        // Ambil 5 log aktivitas terbaru (berdasarkan kolom 'waktu')
        // with('user') → ikut mengambil relasi data user yang melakukan aktivitas
        $recentLogs = Log_aktivitas::with('user')
            ->orderBy('waktu', 'desc') // urutkan dari terbaru
            ->take(5)                  // batasi hanya 5 data
            ->get();                   // ambil hasil query

        // Kirim semua data ke view 'admin.dashboard'
        // compact() membuat array dari variabel dengan nama yang sama
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalManagers',
            'totalPasien',
            'totalMenus',
            'recentLogs'
        ));
    }
}
