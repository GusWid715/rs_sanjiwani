<?php

namespace App\Http\Controllers\Gizi;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Traits\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // memulai database transaction
        DB::transaction(function () use ($pesanan) {
            // mengambil data paket dan semua menu di dalamnya
            // load() digunakan untuk eager loading relasi
            $pesanan->load('paketMakanan.menu');

            // validasi stok sebelum pengurangan
            foreach ($pesanan->paketMakanan->menu as $menu) {
                    // cek jika stok menu kurang dari atau sama dengan 0
                    if ($menu->stok <= 0) {
                        // jika stok habis, batalkan seluruh proses dengan melempar exception
                        throw new \Exception('Tidak dapat menyelesaikan pesanan. Stok untuk menu "' . $menu->nama_menu . '" telah habis.');
                    }
                }

            // loop setiap menu di dalam paket
            foreach ($pesanan->paketMakanan->menu as $menu) {
                // kurangi stok menu sebanyak 1
                $menu->decrement('stok');
            }

            // update status pesanan menjadi 'selesai'
            $pesanan->update(['status' => 'selesai']);

            // catat aktivitas ke log
            $this->logActivity('menyelesaikan pesanan #' . $pesanan->id . ' dan mengurangi stok', 'pesanan', $pesanan->id);
        });

        return redirect()->route('manager.pesanan.index')->with('success', 'Pesanan #' . $pesanan->id . ' telah selesai dan stok menu telah diperbarui.');
    }

    // fungsi untuk mengubah status pesanan menjadi 'batal'
    public function cancel(Request $request, Pesanan $pesanan)
    {
        // validasi bahwa alasan batal wajib diisi
        $request->validate([
            'alasan_batal' => 'required|string|min:5',
        ]);

        // update status dan simpan alasan pembatalan
        $pesanan->update([
            'status' => 'batal',
            'alasan_batal' => $request->alasan_batal,
        ]);

        // catat log
        $this->logActivity('membatalkan pesanan #' . $pesanan->id, 'pesanan', $pesanan->id);
        
        return redirect()->route('manager.pesanan.index')->with('success', 'Pesanan #' . $pesanan->id . ' telah dibatalkan.');
    }
}
