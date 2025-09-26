<?php

namespace App\Http\Controllers\Gizi;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Traits\ActivityLogger;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    use ActivityLogger; // menggunakan trait untuk pencatatan log

    // fungsi untuk menampilkan halaman utama pesanan
    public function index()
    {
        // mengambil semua pesanan yang statusnya 'pending'
        $pendingPesanan = Pesanan::with(['ruangRawat', 'paketMakanan'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        // mengambil semua pesanan yang statusnya 'proses'
        $prosesPesanan = Pesanan::with(['ruangRawat', 'paketMakanan'])
            ->where('status', 'proses')
            ->latest()
            ->get();

        // mengirim kedua data tersebut ke view
        return view('Gizi.pesanan.index', compact('pendingPesanan', 'prosesPesanan'));
    }

    // fungsi untuk mengubah status pesanan dari 'pending' menjadi 'proses'
    public function process(Pesanan $pesanan)
    {
        $pesanan->update(['status' => 'proses']); // update status
        $this->logActivity('memproses pesanan #' . $pesanan->id, 'pesanan', $pesanan->id); // catat log
        return redirect()->route('manager.pesanan.index')->with('success', 'Pesanan #' . $pesanan->id . ' telah diproses.');
    }

    // fungsi untuk mengubah status pesanan dari 'proses' menjadi 'selesai'
    public function complete(Pesanan $pesanan)
    {
        $pesanan->update(['status' => 'selesai']); // update status
        $this->logActivity('menyelesaikan pesanan #' . $pesanan->id, 'pesanan', $pesanan->id); // catat log
        return redirect()->route('manager.pesanan.index')->with('success', 'Pesanan #' . $pesanan->id . ' telah selesai.');
    }

    // fungsi untuk mengubah status pesanan menjadi 'batal'
    public function cancel(Pesanan $pesanan)
    {
        $pesanan->update(['status' => 'batal']); // update status
        $this->logActivity('membatalkan pesanan #' . $pesanan->id, 'pesanan', $pesanan->id); // catat log
        return redirect()->route('manager.pesanan.index')->with('success', 'Pesanan #' . $pesanan->id . ' telah dibatalkan.');
    }
}
