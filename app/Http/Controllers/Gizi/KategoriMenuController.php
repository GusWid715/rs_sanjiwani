<?php

namespace App\Http\Controllers\Gizi;

use App\Http\Controllers\Controller;
use App\Models\KategoriMenu; // menggunakan model KategoriMenu
use App\Traits\ActivityLogger; // menggunakan trait untuk log
use Illuminate\Http\Request;

class KategoriMenuController extends Controller
{
    use ActivityLogger; // menggunakan trait di dalam controller

    // menampilkan halaman daftar semua kategori
    public function index()
    {
        $kategoriMenu = KategoriMenu::latest()->paginate(10); // mengambil data terbaru
        return view('Gizi.kategori.index', compact('kategoriMenu'));
    }

    // menampilkan form untuk membuat kategori baru
    public function create()
    {
        return view('Gizi.kategori.create');
    }

    // menyimpan data kategori baru ke database
    public function store(Request $request)
    {
        // validasi input
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:50|unique:kategori_menu,nama_kategori',
        ]);

        // membuat record baru
        $kategori = KategoriMenu::create($data);

        // mencatat aktivitas ke log
        $this->logActivity('membuat kategori menu: ' . $kategori->nama_kategori, 'kategori_menu', $kategori->id);

        return redirect()->route('manager.kategori.index')->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    // menampilkan form untuk mengedit kategori
    public function edit(KategoriMenu $kategoriMenu)
    {
        return view('Gizi.kategori.edit', compact('kategoriMenu'));
    }

    // memperbarui data kategori di database
    public function update(Request $request, KategoriMenu $kategoriMenu)
    {
        // validasi input
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:50|unique:kategori_menu,nama_kategori,' . $kategoriMenu->id,
        ]);

        // memperbarui record
        $kategoriMenu->update($data);

        // mencatat aktivitas ke log
        $this->logActivity('memperbarui kategori menu: ' . $kategoriMenu->nama_kategori, 'kategori_menu', $kategoriMenu->id);

        return redirect()->route('manager.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    // menghapus data kategori dari database
    public function destroy(KategoriMenu $kategoriMenu)
    {
        // cek jika kategori masih memiliki menu
        if ($kategoriMenu->menu()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus kategori karena masih ada menu yang terhubung.');
        }

        // mencatat aktivitas ke log
        $this->logActivity('menghapus kategori menu: ' . $kategoriMenu->nama_kategori, 'kategori_menu', $kategoriMenu->id);
        
        // menghapus record
        $kategoriMenu->delete();

        return redirect()->route('manager.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}