<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\pesanans;
use App\Models\menus;

class PesananController extends Controller
{
    // ================== LIST PESANAN ==================
    public function index()
    {
        $userId = Auth::id(); // id user yang login
        $pesanans = pesanans::where('user_id', $userId)
                            ->latest()
                            ->paginate(10);

        return view('user.pesanan.index', compact('pesanans'));
    }

    // ================== FORM BUAT PESANAN ==================
    public function create(Request $request)
{
    $menuId = $request->get('menu_id');
    $menu = $menuId ? \App\Models\menus::find($menuId) : null;
    $menus = \App\Models\menus::all(); // untuk dropdown semua menu
    $selectedMenu = null;
        if ($menuId) {
            $selectedMenu = Menus::findOrFail($menuId);
        }

    return view('user.pesanan.create', compact('menu', 'menus', 'selectedMenu'));
}


    // ================== SIMPAN PESANAN ==================
    public function store(Request $request)
    {
        $request->validate([
            'menu_id'   => 'required|exists:menus,id',
            'jumlah'    => 'required|integer|min:1',
            'ruangan'   => 'nullable|string|max:100',
        ]);

        pesanans::create([
            'user_id'   => Auth::id(),
            'menu_id'   => $request->menu_id,
            'jumlah'    => $request->jumlah,
            'ruangan'   => $request->ruangan,
            'status'    => 'pending', // default
        ]);

        return redirect()->route('user.pesanan.index')
                        ->with('success', 'Pesanan berhasil dibuat.');
    }

    // ================== DETAIL PESANAN ==================
    public function show(pesanans $pesanan)
    {
        if ($pesanan->user_id !== Auth::id()) {
            abort(403); // tidak boleh lihat pesanan orang lain
        }

        return view('user.pesanan.show', compact('pesanan'));
    }

    // ================== EDIT PESANAN ==================
    public function edit(pesanans $pesanan)
    {
        if ($pesanan->user_id !== Auth::id()) {
            abort(403);
        }

        $menus = menus::all();
        return view('user.pesanan.edit', compact('pesanan', 'menus'));
    }

    // ================== UPDATE PESANAN ==================
    public function update(Request $request, pesanans $pesanan)
    {
        if ($pesanan->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'menu_id'   => 'required|exists:menus,id',
            'jumlah'    => 'required|integer|min:1',
            'alamat'    => 'required|string|max:255',
            'ruangan'   => 'nullable|string|max:100',
            'no_ruangan'=> 'nullable|string|max:20',
        ]);

        $pesanan->update([
            'menu_id'   => $request->menu_id,
            'jumlah'    => $request->jumlah,
            'alamat'    => $request->alamat,
            'ruangan'   => $request->ruangan,
            'no_ruangan'=> $request->no_ruangan,
        ]);

        return redirect()->route('user.pesanan.index')
                         ->with('success', 'Pesanan berhasil diperbarui.');
    }

    // ================== HAPUS PESANAN ==================
    public function destroy(pesanans $pesanan)
    {
        if ($pesanan->user_id !== Auth::id()) {
            abort(403);
        }

        $pesanan->delete();

        return redirect()->route('user.pesanan.index')
                         ->with('success', 'Pesanan berhasil dihapus.');
    }
}
