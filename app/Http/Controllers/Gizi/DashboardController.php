<?php

namespace App\Http\Controllers\Gizi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\pesanans;
use App\Models\menus;
use App\Models\log_aktivitas;

class DashboardController extends Controller
{
    // tampilkan dashboard Gizi (satu method, mengirimkan semua data ke view)
    public function dashboard(Request $request)
    {
        // ringkasan status pesanan
        $totalPending  = pesanans::where('status', 'pending')->count();
        $totalProses   = pesanans::where('status', 'proses')->count();
        $totalSelesai  = pesanans::where('status', 'selesai')->count();
        $totalDitolak  = pesanans::where('status', 'ditolak')->count();

        // jumlah menu
        $totalMenus = menus::count();

        // daftar pesanan pending terbaru (limit 10)
        $incomingPesanans = pesanans::with(['user', 'menu'])
            ->where('status', 'pending')
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // log aktivitas terbaru (limit 10)
        $recentLogs = log_aktivitas::with('user')
            ->orderByDesc('waktu')
            ->take(10)
            ->get();

        return view('Gizi.dashboard', compact(
            'totalPending',
            'totalProses',
            'totalSelesai',
            'totalDitolak',
            'totalMenus',
            'incomingPesanans',
            'recentLogs'
        ));
    }
}
