<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kategori_makanans;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Tampilkan halaman dashboard user berisi menu grouped by kategori.
     */
    public function index(Request $request)
    {
        // Ambil kategori + menus terkait yang stok > 0 (tersedia)
        $categories = Kategori_makanans::with(['menus' => function($q) {
            $q->where('stok', '>', 0)->orderBy('nama_menu');
        }])->orderBy('nama_kategori')->get();

        return view('user.dashboard', compact('categories'));
    }
}
