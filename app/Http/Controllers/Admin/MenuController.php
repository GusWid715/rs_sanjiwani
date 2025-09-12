<?php

namespace App\Http\Controllers\Admin;
// Namespace untuk menandai controller ini berada di folder Admin

use App\Http\Controllers\Controller; 
// Import base controller dari Laravel

use App\Models\menus; 
// Model menus → untuk mengelola data tabel menu makanan

use App\Models\kategori_makanans; 
// Model kategori_makanans → untuk mengelola data kategori makanan

use Illuminate\Http\Request; 
// Digunakan untuk mengambil input (request) dari form atau query string

class MenuController extends Controller
{
    // ================== MENAMPILKAN LIST MENU ==================
    public function index(Request $request)
    {
        // Ambil parameter pencarian (q) dari query string
        $q = $request->input('q');

        // Ambil parameter filter kategori dari query string
        $kategori = $request->input('kategori');

        // Buat query builder, dengan relasi ke kategori
        $query = menus::with('kategori');

        // Jika ada pencarian (q), filter berdasarkan nama_menu atau deskripsi
        if ($q) {
            $query->where('nama_menu', 'like', "%{$q}%")
                ->orWhere('deskripsi', 'like', "%{$q}%");
        }

        // Jika ada filter kategori, tambahkan kondisi where kategori_id
        if ($kategori) {
            $query->where('kategori_id', $kategori);
        }

        // Urutkan data menu dari ID terbesar (terbaru) dan paginasi 10 per halaman
        $menus = $query->orderByDesc('id')->paginate(10);

        // Ambil semua kategori makanan untuk dropdown/filter di UI
        $kategoris = kategori_makanans::orderBy('nama_kategori')->get();

        // Kirim data menus, kategoris, q, dan kategori ke view
        return view('admin.menus.index', compact('menus','kategoris','q','kategori'));
    }

    // ================== FORM TAMBAH MENU ==================
    public function create()
    {
        // Ambil semua kategori makanan untuk pilihan dropdown di form create
        $kategoris = kategori_makanans::orderBy('nama_kategori')->get();

        // Tampilkan form tambah menu
        return view('admin.menus.create', compact('kategoris'));
    }

    // ================== SIMPAN DATA MENU BARU ==================
    public function store(Request $request)
    {
        // Validasi input form
        $data = $request->validate([
            'nama_menu' => 'required|string|max:100', // nama menu wajib, max 100 karakter
            'kategori_id' => 'required|exists:kategori_menus,id', // kategori harus ada di tabel kategori_menus
            'deskripsi' => 'nullable|string', // deskripsi boleh kosong
            'stok' => 'required|integer|min:0', // stok wajib, harus angka >= 0
        ]);

        // Simpan data menu baru ke database
        menus::create($data);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    // ================== FORM DETAIL MENU ==================
    public function show(menus $menu)
    {
        // Load relasi kategori agar bisa ditampilkan di detail
        $menu->load('kategori');

        // Tampilkan halaman detail menu
        return view('admin.menus.show', compact('menu'));
    }

    // ================== FORM EDIT MENU ==================
    public function edit(menus $menu)
    {
        // Ambil semua kategori makanan untuk dropdown di form edit
        $kategoris = kategori_makanans::orderBy('nama_kategori')->get();

        // Tampilkan form edit menu
        return view('admin.menus.edit', compact('menu','kategoris'));
    }

    // ================== UPDATE DATA MENU ==================
    public function update(Request $request, menus $menu)
    {
        // Validasi input form
        $data = $request->validate([
            'nama_menu' => 'required|string|max:100', // nama menu wajib
            'kategori_id' => 'required|exists:kategori_menus,id', // kategori harus valid
            'deskripsi' => 'nullable|string', // deskripsi boleh kosong
            'stok' => 'required|integer|min:0', // stok wajib
        ]);

        // Update data menu di database
        $menu->update($data);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    // ================== FORM HAPUS MENU ==================
    public function destroy(menus $menu)
    {
        // Hapus data menu dari database
        $menu->delete();

        // Redirect ke index dengan pesan sukses
        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus.');
    }
}
