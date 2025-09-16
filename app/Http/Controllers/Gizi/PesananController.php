<?php

namespace App\Http\Controllers\Gizi;

use App\Http\Controllers\Controller;
use App\Models\pesanans; // gunakan model pesanans
use Illuminate\Http\Request;
use App\Traits\ActivityLogger; // import trait

class PesananController extends Controller
{
    use ActivityLogger; // gunakan trait

    // menampilkan daftar pesanan yang pending saja
    public function index()
    {
        // UBAH BARIS INI: tambahkan 'menu.set' untuk mengambil data set
        $pesanans = pesanans::with(['user', 'menu.set'])
            ->where('status', 'pending')
            ->orderBy('tanggal', 'asc') // tampilkan yang paling lama di atas
            ->get();

        return view('Gizi.pesanan.index', compact('pesanans'));
    }

    // fungsi untuk menyetujui pesanan
    public function approve(pesanans $pesanan)
    {
        // ubah status menjadi 'selesai'
        $pesanan->status = 'selesai';
        $pesanan->save();

        // catat aktivitas ke log
        $logMessage = 'menyelesaikan pesanan #' . $pesanan->id . ' untuk pasien ' . ($pesanan->user->name ?? 'N/A');
        $this->logActivity($logMessage, 'pesanans', $pesanan->id);

        // kembali ke halaman pesanan masuk dengan pesan sukses
        return redirect()->route('gizi.pesanan.index')
                         ->with('success', 'Pesanan telah diselesaikan.');
    }
}