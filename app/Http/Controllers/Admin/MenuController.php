<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\menus;
use App\Models\kategori_makanans;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $kategori = $request->input('kategori');

        $query = menus::with('kategori');

        if ($q) {
            $query->where('nama_menu', 'like', "%{$q}%")
                  ->orWhere('deskripsi', 'like', "%{$q}%");
        }

        if ($kategori) {
            $query->where('kategori_id', $kategori);
        }

        $menus = $query->orderByDesc('id')->paginate(10);
        $kategoris = kategori_makanans::orderBy('nama_kategori')->get();

        return view('admin.menus.index', compact('menus','kategoris','q','kategori'));
    }

    public function create()
    {
        $kategoris = kategori_makanans::orderBy('nama_kategori')->get();
        return view('admin.menus.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_menu' => 'required|string|max:100',
            'kategori_id' => 'required|exists:kategori_menus,id',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
        ]);

        menus::create($data);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function show(menus $menu)
    {
        $menu->load('kategori');
        return view('admin.menus.show', compact('menu'));
    }

    public function edit(menus $menu)
    {
        $kategoris = kategori_makanans::orderBy('nama_kategori')->get();
        return view('admin.menus.edit', compact('menu','kategoris'));
    }

    public function update(Request $request, menus $menu)
    {
        $data = $request->validate([
            'nama_menu' => 'required|string|max:100',
            'kategori_id' => 'required|exists:kategori_menus,id',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
        ]);

        $menu->update($data);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(menus $menu)
    {
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus.');
    }
}
