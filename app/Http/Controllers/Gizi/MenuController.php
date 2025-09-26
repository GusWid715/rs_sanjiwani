<?php

namespace App\Http\Controllers\Gizi;

use App\Http\Controllers\Controller;
use App\Models\KategoriMenu;
use App\Models\Menu;
use App\Traits\ActivityLogger;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    use ActivityLogger; // menggunakan trait untuk log

    // menampilkan halaman daftar semua menu
    public function index(Request $request)
    {
        // memulai query ke model Menu
        $query = Menu::with('kategoriMenu'); // with() untuk mengambil data relasi kategori

        // filter berdasarkan pencarian nama menu
        if ($request->filled('q')) {
            $q = $request->q;
            // menambahkan grup kondisi WHERE untuk pencarian
            $query->where(function ($subQuery) use ($q) {
                // kondisi 1: cari di kolom 'nama_menu' di tabel 'menu'
                $subQuery->where('nama_menu', 'like', '%' . $q . '%')
                         // kondisi 2: atau cari di relasi 'kategoriMenu'
                        ->orWhereHas('kategoriMenu', function ($kategoriQuery) use ($q) {
                             // di dalam relasi, cari di kolom 'nama_kategori'
                            $kategoriQuery->where('nama_kategori', 'like', '%' . $q . '%');
                        });
            });
        }

        // filter berdasarkan kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // mengambil data menu yang sudah difilter dan diurutkan
        $menu = $query->latest()->paginate(10);
        
        // mengambil semua kategori untuk dropdown filter
        $kategoriMenu = KategoriMenu::orderBy('nama_kategori')->get();

        return view('Gizi.menu.index', [
            'menu' => $menu,
            'kategoriMenu' => $kategoriMenu,
            'q' => $request->q,
            'kategori_id' => $request->kategori_id
        ]);
    }

    // menampilkan form untuk membuat menu baru
    public function create()
    {
        $kategoriMenu = KategoriMenu::orderBy('nama_kategori')->get();
        return view('Gizi.menu.create', compact('kategoriMenu'));
    }

    // menyimpan menu baru ke database
    public function store(Request $request)
    {
        // validasi input
        $data = $request->validate([
            'nama_menu' => 'required|string|max:100',
            'kategori_id' => 'required|exists:kategori_menu,id',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
        ]);

        $menu = Menu::create($data);
        $this->logActivity('membuat menu: ' . $menu->nama_menu, 'menu', $menu->id);

        return redirect()->route('manager.menu.index')->with('success', 'Menu baru berhasil ditambahkan.');
    }

    // menampilkan form untuk mengedit menu
    public function edit(Menu $menu)
    {
        $kategoriMenu = KategoriMenu::orderBy('nama_kategori')->get();
        return view('Gizi.menu.edit', compact('menu', 'kategoriMenu'));
    }

    // memperbarui menu di database
    public function update(Request $request, Menu $menu)
    {
        // validasi input
        $data = $request->validate([
            'nama_menu' => 'required|string|max:100',
            'kategori_id' => 'required|exists:kategori_menu,id',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
        ]);

        $menu->update($data);
        $this->logActivity('memperbarui menu: ' . $menu->nama_menu, 'menu', $menu->id);

        return redirect()->route('manager.menu.index')->with('success', 'Menu berhasil diperbarui.');
    }

    // menghapus menu dari database
    public function destroy(Menu $menu)
    {
        $this->logActivity('menghapus menu: ' . $menu->nama_menu, 'menu', $menu->id);
        $menu->delete();
        return redirect()->route('manager.menu.index')->with('success', 'Menu berhasil dihapus.');
    }
}