<?php

namespace App\Http\Controllers\Gizi;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\PaketMakanan;
use App\Models\Pesanan;
use App\Models\log_aktivitas;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Menghitung statistik utama
        $totalPesananPending = Pesanan::where('status', 'pending')->count();
        $totalPaket = PaketMakanan::count();
        $totalMenu = Menu::count();

        // Mengambil 5 pesanan terbaru yang statusnya 'pending' untuk preview
        $recentPesanan = Pesanan::with(['ruangRawat', 'paketMakanan'])
            ->where('status', 'pending')
            ->latest() // Mengurutkan berdasarkan data terbaru
            ->take(5)
            ->get();

        // Mengambil 5 log aktivitas terbaru
        $recentLogs = log_aktivitas::with('user')
            ->latest('created_at')
            ->take(5)
            ->get();

        // Mengirim semua data ke view
        return view('Gizi.dashboard', compact(
            'totalPesananPending',
            'totalPaket',
            'totalMenu',
            'recentPesanan',
            'recentLogs'
        ));
    }
}