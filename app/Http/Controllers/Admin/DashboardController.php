<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log_aktivitas;
use App\Models\User;
use App\Models\menus;

class DashboardController extends Controller
{

  public function dashboard()
    {
        $totalUsers   = User::count();
        $totalAdmins  = User::where('role', 'admin')->count();
        $totalManagers = User::where('role', 'manager')->count();
        $totalPasien  = User::where('role', 'pasien')->count();
        $totalMenus   = menus::count();

        // Ambil 5 log terbaru
        $recentLogs = Log_aktivitas::with('user')
            ->orderBy('waktu', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalManagers',
            'totalPasien',
            'totalMenus',
            'recentLogs'
        ));
    }

    public function index()
    {
        $totalUsers = User::count();
        $totalMenus = menus::count();

        return view('admin.dashboard', compact('totalUsers', 'totalMenus'));
    }
}
