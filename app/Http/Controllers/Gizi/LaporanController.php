<?php

namespace App\Http\Controllers\Gizi;

use App\Http\Controllers\Controller;
use App\Models\PaketMakanan;
use App\Models\Pesanan;
use App\Traits\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    use ActivityLogger; // trait untuk log aktivitas

    public function index(Request $request)
    {
        // mengambil filter tanggal dan status
        $end = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();
        $start = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->subDays(6)->startOfDay();
        $status = $request->input('status');

        // query dasar ke model Pesanan
        $query = Pesanan::query()->whereBetween('tanggal', [$start, $end]);

        if (!empty($status)) {
            $query->where('status', $status);
        }

        // mengambil data pesanan beserta relasinya (nama ruang rawat & nama paket)
        $pesanan = $query->with(['ruangRawat', 'paketMakanan'])->orderByDesc('tanggal')->get();

        // menghitung total dan ringkasan per status dari data yang sudah diambil
        $total = $pesanan->count();
        $byStatus = $pesanan->groupBy('status')->mapWithKeys(function ($group, $key) {
            return [$key => (object)['total' => $group->count()]];
        });

        // menghitung top paket makanan
        $totalsByPaket = DB::table('pesanan')
            ->select('paket_makanan_id', DB::raw('COUNT(*) as total_pesanan'))
            ->whereBetween('tanggal', [$start, $end])
            ->when(!empty($status), fn($q) => $q->where('status', '!=', 'batal'))
            ->groupBy('paket_makanan_id')
            ->orderByDesc('total_pesanan')
            ->get();
            
        // mengambil detail paket makanan untuk ditampilkan namanya
        $paketIds = $totalsByPaket->pluck('paket_makanan_id')->filter()->unique()->values()->all();
        $paketMakanan = [];
        if (!empty($paketIds)) {
            $paketMakanan = PaketMakanan::whereIn('id', $paketIds)->pluck('nama_paket', 'id');
        }

        // kirim semua data ke view
        return view('Gizi.laporan.index', compact('pesanan', 'total', 'byStatus', 'totalsByPaket', 'paketMakanan', 'start', 'end', 'status'));
    }

    // fungsi untuk ekspor laporan ke CSV
    public function export(Request $request)
    {
        // mengambil filter tanggal dan status
        $end = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();
        $start = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->subDays(6)->startOfDay();
        $status = $request->input('status');

        // Query data pesanan dengan join untuk mendapatkan nama
        $query = DB::table('pesanan')
            ->join('ruang_rawat', 'pesanan.ruang_rawat_id', '=', 'ruang_rawat.id')
            ->join('paket_makanan', 'pesanan.paket_makanan_id', '=', 'paket_makanan.id')
            ->select('ruang_rawat.nama_ruang', 'ruang_rawat.lokasi', 'paket_makanan.nama_paket', 'pesanan.tanggal', 'pesanan.status')
            ->whereBetween('pesanan.tanggal', [$start, $end]);
        
        if (!empty($status)) {
            $query->where('pesanan.status', $status);
        }
        $pesananRows = $query->orderBy('pesanan.tanggal')->cursor();

        // Query untuk Top Paket Makanan
        $topPaketRows = DB::table('pesanan')
            ->join('paket_makanan', 'pesanan.paket_makanan_id', '=', 'paket_makanan.id')
            ->select('paket_makanan.nama_paket', DB::raw('COUNT(pesanan.id) as total_pesanan'))
            ->whereBetween('pesanan.tanggal', [$start, $end])
            ->when(!empty($status), fn($q) => $q->where('status', '!=', 'batal'))
            ->groupBy('paket_makanan.nama_paket')
            ->orderByDesc('total_pesanan')
            ->get();

        // mencatat aktivitas ke log
        $this->logActivity('mengekspor laporan pesanan', 'laporan', 0);

        $filename = 'laporan_pesanan_' . $start->format('Ymd') . '-' . $end->format('Ymd') . '.csv';

        // membuat response untuk men-download file
        return new StreamedResponse(function () use ($pesananRows, $topPaketRows) {
            $handle = fopen('php://output', 'w');

            // Header untuk tabel utama
            fputcsv($handle, ['Ruang Rawat', 'Lokasi', 'Paket Makanan', 'Tanggal', 'Status']);
            
            // Data untuk tabel utama
            foreach ($pesananRows as $row) {
                fputcsv($handle, [
                    $row->nama_ruang,
                    $row->lokasi,
                    $row->nama_paket,
                    $row->tanggal,
                    $row->status,
                ]);
            }

            // Memberi jarak kosong sebelum ringkasan
            fputcsv($handle, []);
            fputcsv($handle, ['Top Paket Makanan (Berdasarkan Jumlah Pesanan)']);

            // Header untuk tabel ringkasan
            fputcsv($handle, ['Nama Paket', 'Total Pesanan']);

            // Data untuk tabel ringkasan
            foreach ($topPaketRows as $row) {
                fputcsv($handle, [
                    $row->nama_paket,
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

