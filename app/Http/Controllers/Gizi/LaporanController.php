<?php

namespace App\Http\Controllers\Gizi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Traits\ActivityLogger;

class LaporanController extends Controller
{
    use ActivityLogger; // trait di dalam controller
    public function index(Request $request)
    {
        // default: 7 hari terakhir
        $end = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();
        $start = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->subDays(6)->startOfDay();
        $status = $request->input('status');

        $query = DB::table('pesanans')
            ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()]);

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $pesanans = $query->orderByDesc('tanggal')->get();

        // ringkasan sederhana
        $total = $pesanans->count();
        $byStatus = $pesanans->groupBy('status')->map->count();

        // totals per menu (jika menu_id ada)
        $totalsByMenu = DB::table('pesanans')
            ->select('menu_id', DB::raw('SUM(jumlah) as total_jumlah'), DB::raw('COUNT(*) as total_pesanan'))
            ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->when(!empty($status), fn($q) => $q->where('status', $status))
            ->groupBy('menu_id')
            ->orderByDesc('total_jumlah')
            ->get();

        // jika ingin mengambil nama menu, coba join ke tabel menus bila ada
        $menuIds = $totalsByMenu->pluck('menu_id')->filter()->unique()->values()->all();
        $menus = [];
        if (!empty($menuIds)) {
            $menus = DB::table('menus')->whereIn('id', $menuIds)->get()->keyBy('id');
        }

        return view('Gizi.laporan.index', compact('pesanans','total','byStatus','totalsByMenu','menus','start','end','status'));
    }

    // Export CSV dengan format laporan kustom
    public function export(Request $request)
    {
        // mengambil filter tanggal dan status
        $end = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();
        $start = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->subDays(6)->startOfDay();
        $status = $request->input('status');

        // Query utama untuk data pesanan (dengan join ke tabel users dan menus)
        $query = DB::table('pesanans')
            ->join('users', 'pesanans.user_id', '=', 'users.id')
            ->join('menus', 'pesanans.menu_id', '=', 'menus.id')
            ->select('users.name as user_name', 'menus.nama_menu', 'pesanans.jumlah', 'pesanans.tanggal', 'pesanans.status', 'pesanans.ruangan', 'pesanans.catatan')
            ->whereBetween('pesanans.tanggal', [$start, $end]);

        if (!empty($status)) {
            $query->where('pesanans.status', $status);
        }
        
        $pesananRows = $query->orderByDesc('pesanans.tanggal')->get();

        // Query kedua untuk ringkasan Top Menu
        $topMenuRows = DB::table('pesanans')
            ->join('menus', 'pesanans.menu_id', '=', 'menus.id')
            ->select('menus.nama_menu', DB::raw('SUM(pesanans.jumlah) as total_jumlah'), DB::raw('COUNT(pesanans.id) as total_pesanan'))
            ->whereBetween('pesanans.tanggal', [$start, $end])
            ->groupBy('menus.nama_menu')
            ->orderByDesc('total_jumlah')
            ->get();

        // 3. catat aktivitas ekspor laporan
        $this->logActivity('mengekspor laporan pesanan', 'laporan', 0);

        $filename = 'laporan_pesanan_' . $start->format('Ymd') . '-' . $end->format('Ymd') . '.csv';

        // membuat response untuk men-download file CSV
        return new StreamedResponse(function() use ($pesananRows, $topMenuRows) {
            $handle = fopen('php://output', 'w');

            // Header untuk data pesanan
            fputcsv($handle, ['User', 'Menu', 'Jumlah', 'Tanggal', 'Status', 'Ruangan', 'Catatan']);

            // Baris data pesanan
            foreach ($pesananRows as $row) {
                fputcsv($handle, [
                    $row->user_name,
                    $row->nama_menu,
                    $row->jumlah,
                    $row->tanggal,
                    $row->status,
                    $row->ruangan,
                    $row->catatan ?? '',
                ]);
            }

            // Memberi jarak kosong sebelum ringkasan
            fputcsv($handle, []);
            fputcsv($handle, ['Top Menu (Berdasarkan total jumlah)']);

            // Header untuk ringkasan Top Menu
            fputcsv($handle, ['Menu', 'Total Jumlah', 'Total Pesanan']);

            // Baris data ringkasan Top Menu
            foreach ($topMenuRows as $row) {
                fputcsv($handle, [
                    $row->nama_menu,
                    $row->total_jumlah,
                    $row->total_pesanan,
                ]);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}