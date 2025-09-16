<?php

namespace App\Http\Controllers\Gizi;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    // menampilkan daftar pesanan masuk (sederhana)
    public function index()
    {
        // ambil semua pesanan terbaru
        $pesanans = DB::table('pesanans')->orderByDesc('tanggal')->get();

        return view('Gizi.pesanan.index', compact('pesanans'));
    }
}
