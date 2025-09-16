<?php

namespace App\Http\Controllers\Gizi; // DIUBAH: dari Admin ke Gizi

use App\Http\Controllers\Controller;
use App\Models\menus;
use App\Models\sets; // DIUBAH: dari kategori_makanans ke sets
use Illuminate\Http\Request;
use App\Traits\ActivityLogger;

class MenuController extends Controller
{
    use ActivityLogger;

    // ================== FORM TAMBAH MENU ==================
    public function create(Request $request) // Ditambahkan Request
    {
        $sets = sets::orderBy('nama_set')->get(); // DIUBAH: dari kategoris ke sets
        $selectedSetId = $request->query('set_id'); // Menangkap set_id dari URL

        // DIUBAH: path view dan mengirim set_id yang dipilih
        return view('Gizi.menus.create', compact('sets', 'selectedSetId'));
    }

    // ================== SIMPAN DATA MENU BARU ==================
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_menu' => 'required|string|max:100',
            'set_id'    => 'required|exists:sets,id', // DIUBAH: validasi ke tabel sets
            'deskripsi' => 'nullable|string',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'stok'      => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $filename = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $filename);
            $data['image'] = $filename;
        }

        $menu = menus::create($data);

        // panggil fungsi log setelah data dibuat
        $this->logActivity('menambah menu baru: ' . $menu->nama_menu, 'menus', $menu->id);

        // DIUBAH: route redirect ke gizi
        return redirect()->route('gizi.sets.show', $menu->set_id)->with('success', 'Menu berhasil ditambahkan.');
    }

    // ================== FORM DETAIL MENU ==================
    public function show(menus $menu)
    {
        $menu->load('set'); // DIUBAH: relasi ke set
        return view('Gizi.menus.show', compact('menu')); // DIUBAH: path view
    }

    // ================== FORM EDIT MENU ==================
    public function edit(menus $menu)
    {
        $sets = sets::orderBy('nama_set')->get(); // DIUBAH: dari kategoris ke sets
        return view('Gizi.menus.edit', compact('menu','sets')); // DIUBAH: path view
    }

    // ================== UPDATE DATA MENU ==================
    public function update(Request $request, menus $menu)
    {
        $data = $request->validate([
            'nama_menu' => 'required|string|max:100',
            'set_id'    => 'required|exists:sets,id', // DIUBAH: validasi ke tabel sets
            'deskripsi' => 'nullable|string',
            'stok'      => 'required|integer|min:0',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($menu->image && file_exists(public_path('images/' . $menu->image))) {
                unlink(public_path('images/' . $menu->image));
            }

            $filename = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $filename);
            $data['image'] = $filename;
        }

        $menu->update($data);

        // panggil fungsi log setelah data diupdate
        $this->logActivity('memperbarui menu: ' . $menu->nama_menu, 'menus', $menu->id);

        // DIUBAH: route redirect ke gizi
        return redirect()->route('gizi.sets.show', $menu->set_id)->with('success', 'Menu berhasil diperbarui.');
    }

    // ================== HAPUS MENU ==================
    public function destroy(menus $menu)
    {
        // Hapus gambar jika ada
        if ($menu->image && file_exists(public_path('images/' . $menu->image))) {
            unlink(public_path('images/' . $menu->image));
        }
        
        // panggil fungsi log sebelum data dihapus
        $this->logActivity('menghapus menu: ' . $menu->nama_menu, 'menus', $menu->id);

        $menu->delete();

        // DIUBAH: route redirect ke gizi
        return redirect()->route('gizi.sets.show', $menu->set_id)->with('success', 'Menu berhasil dihapus.');
    }
}