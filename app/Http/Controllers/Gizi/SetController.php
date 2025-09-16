<?php

namespace App\Http\Controllers\Gizi;

use App\Http\Controllers\Controller;
use App\Models\sets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SetController extends Controller
{
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

        sets::create($request->all());

        Log::info(Auth::user()->name . ' membuat set makanan baru: ' . $request->nama_set);

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

        Log::info(Auth::user()->name . ' memperbarui set makanan: ' . $set->nama_set);

        return redirect()->route('gizi.sets.index')
                        ->with('success', 'Set makanan berhasil diperbarui.');
    }

    public function destroy(sets $set)
    {
        if ($set->menus()->count() > 0) {
            return redirect()->route('gizi.sets.index')
                            ->with('error', 'Tidak dapat menghapus set karena masih ada menu yang terhubung.');
        }
        
        $nama_set_dihapus = $set->nama_set;
        $set->delete();
        
        Log::info(Auth::user()->name . ' menghapus set makanan: ' . $nama_set_dihapus);

        return redirect()->route('gizi.sets.index')
                        ->with('success', 'Set makanan berhasil dihapus.');
    }
}