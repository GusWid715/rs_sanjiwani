<?php

namespace App\Http\Controllers\Gizi;

use App\Http\Controllers\Controller;
use App\Models\KategoriMenu;
use App\Models\Menu;
use App\Models\PaketMakanan;
use App\Traits\ActivityLogger;
use Illuminate\Http\Request;

class PaketMakananController extends Controller
{
    use ActivityLogger; // menggunakan trait untuk log

    // menampilkan halaman daftar semua paket makanan
    public function index()
    {
        $paketMakanan = PaketMakanan::latest()->paginate(10);
        return view('Gizi.paket.index', compact('paketMakanan'));
    }

    // menampilkan form untuk membuat paket baru
    public function create()
    {
        return view('Gizi.paket.create');
    }

    // menyimpan paket baru ke database
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_paket' => 'required|string|max:255|unique:paket_makanan,nama_paket',
            'deskripsi' => 'nullable|string',
        ]);

        $paket = PaketMakanan::create($data);
        $this->logActivity('membuat paket makanan: ' . $paket->nama_paket, 'paket_makanan', $paket->id);

        // setelah membuat, langsung arahkan ke halaman perakitan
        return redirect()->route('manager.paket-makanan.show', $paket->id)->with('success', 'Paket berhasil dibuat. Silakan tambahkan menu ke dalam paket.');
    }

    // menampilkan halaman perakitan paket (detail)
    public function show(Request $request, PaketMakanan $paketMakanan)
    {
        // mengambil menu yang sudah ada di dalam paket ini
        $menuDalamPaket = $paketMakanan->menu()->pluck('menu.id')->toArray();

        // mengambil semua kategori untuk filter dropdown
        $kategoriMenu = KategoriMenu::orderBy('nama_kategori')->get();

        // memulai query untuk menu yang tersedia (yang belum ada di dalam paket)
        $queryMenuTersedia = Menu::query()->whereNotIn('id', $menuDalamPaket);

        // menerapkan filter kategori jika ada
        if ($request->filled('kategori_id')) {
            $queryMenuTersedia->where('kategori_id', $request->kategori_id);
        }

        $menuTersedia = $queryMenuTersedia->orderBy('nama_menu')->get();

        return view('Gizi.paket.show', compact('paketMakanan', 'kategoriMenu', 'menuTersedia'));
    }

    // menampilkan form untuk mengedit nama/deskripsi paket
    public function edit(PaketMakanan $paketMakanan)
    {
        return view('Gizi.paket.edit', compact('paketMakanan'));
    }

    // memperbarui nama/deskripsi paket di database
    public function update(Request $request, PaketMakanan $paketMakanan)
    {
        $data = $request->validate([
            'nama_paket' => 'required|string|max:255|unique:paket_makanan,nama_paket,' . $paketMakanan->id,
            'deskripsi' => 'nullable|string',
        ]);

        $paketMakanan->update($data);
        $this->logActivity('memperbarui paket makanan: ' . $paketMakanan->nama_paket, 'paket_makanan', $paketMakanan->id);

        return redirect()->route('manager.paket-makanan.index')->with('success', 'Paket berhasil diperbarui.');
    }

    // menghapus paket dari database
    public function destroy(PaketMakanan $paketMakanan)
    {
        $this->logActivity('menghapus paket makanan: ' . $paketMakanan->nama_paket, 'paket_makanan', $paketMakanan->id);
        $paketMakanan->delete();
        return redirect()->route('manager.paket-makanan.index')->with('success', 'Paket berhasil dihapus.');
    }

    // fungsi untuk menambahkan menu ke dalam paket (attach)
    public function attachMenu(Request $request, PaketMakanan $paketMakanan)
    {
        $request->validate(['menu_id' => 'required|exists:menu,id']);
        // attach() menambahkan relasi baru di tabel pivot
        $paketMakanan->menu()->attach($request->menu_id);
        
        $menu = Menu::find($request->menu_id);
        $this->logActivity('menambah menu "' . $menu->nama_menu . '" ke paket "' . $paketMakanan->nama_paket . '"', 'paket_makanan_menu', $paketMakanan->id);

        return back()->with('success', 'Menu berhasil ditambahkan ke paket.');
    }

    // fungsi untuk menghapus menu dari paket (detach)
    public function detachMenu(Request $request, PaketMakanan $paketMakanan)
    {
        $request->validate(['menu_id' => 'required|exists:menu,id']);
        // detach() menghapus relasi dari tabel pivot
        $paketMakanan->menu()->detach($request->menu_id);

        $menu = Menu::find($request->menu_id);
        $this->logActivity('menghapus menu "' . $menu->nama_menu . '" dari paket "' . $paketMakanan->nama_paket . '"', 'paket_makanan_menu', $paketMakanan->id);

        return back()->with('success', 'Menu berhasil dihapus dari paket.');
    }
}