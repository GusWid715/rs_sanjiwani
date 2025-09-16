<?php

namespace App\Http\Controllers\Gizi;

use App\Http\Controllers\Controller;
use App\Models\sets;
use Illuminate\Http\Request;
use App\Traits\ActivityLogger; // import trait

class SetController extends Controller
{
    use ActivityLogger; // gunakan trait di dalam controller

    public function index()
    {
        $sets = sets::latest()->paginate(10);
        return view('Gizi.sets.index', compact('sets'));
    }

    public function create()
    {
        return view('Gizi.sets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_set' => 'required|string|max:255|unique:sets,nama_set',
            'deskripsi' => 'nullable|string',
        ]);

        $set = sets::create($request->all());

        // panggil fungsi log setelah data dibuat
        $this->logActivity('membuat set baru: ' . $set->nama_set, 'sets', $set->id);

        return redirect()->route('gizi.sets.index')
                         ->with('success', 'Set makanan berhasil ditambahkan.');
    }

    public function show(sets $set)
    {
        $set->load('menus');
        return view('Gizi.sets.show', compact('set'));
    }

    public function edit(sets $set)
    {
        return view('Gizi.sets.edit', compact('set'));
    }

    public function update(Request $request, sets $set)
    {
        $request->validate([
            'nama_set' => 'required|string|max:255|unique:sets,nama_set,' . $set->id,
            'deskripsi' => 'nullable|string',
        ]);

        $set->update($request->all());

        // panggil fungsi log setelah data diupdate
        $this->logActivity('memperbarui set: ' . $set->nama_set, 'sets', $set->id);

        return redirect()->route('gizi.sets.index')
                         ->with('success', 'Set makanan berhasil diperbarui.');
    }

    public function destroy(sets $set)
    {
        if ($set->menus()->count() > 0) {
            return redirect()->route('gizi.sets.index')
                             ->with('error', 'Tidak dapat menghapus set karena masih ada menu yang terhubung.');
        }
        
        // panggil fungsi log sebelum data dihapus
        $this->logActivity('menghapus set: ' . $set->nama_set, 'sets', $set->id);
        
        $set->delete();

        return redirect()->route('gizi.sets.index')
                         ->with('success', 'Set makanan berhasil dihapus.');
    }
}