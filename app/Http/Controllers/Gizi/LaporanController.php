<?php

namespace App\Http\Controllers\Gizi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
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

    // Export CSV sederhana
    public function export(Request $request)
    {
        $end = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();
        $start = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->subDays(6)->startOfDay();
        $status = $request->input('status');

        $query = DB::table('pesanans')->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()]);
        if (!empty($status)) $query->where('status', $status);
        $rows = $query->orderByDesc('tanggal')->cursor(); // cursor untuk memory-friendly

        $filename = 'laporan_pesanans_' . $start->format('Ymd') . '_' . $end->format('Ymd') . '.csv';

        $response = new StreamedResponse(function() use ($rows) {
            $out = fopen('php://output','w');
            // header CSV
            fputcsv($out, ['id','user_id','menu_id','jumlah','tanggal','status','ruangan','no_ruangan','catatan','created_at']);
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->id,
                    $r->user_id,
                    $r->menu_id,
                    $r->jumlah,
                    $r->tanggal,
                    $r->status,
                    $r->ruangan ?? '',
                    $r->no_ruangan ?? '',
                    $r->catatan ?? '',
                    $r->created_at ?? ''
                ]);
            }
            fclose($out);
        });

        $response->headers->set('Content-Type','text/csv');
        $response->headers->set('Content-Disposition','attachment; filename="'.$filename.'"');

        return $response;
    }
}
