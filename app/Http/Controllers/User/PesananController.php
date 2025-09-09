<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Detail_pesanans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Log_aktivitas;
use App\Models\menus;
use App\Models\pesanans;

class PesananController extends Controller
{
    // Tampilkan daftar pesanan milik user yang login
    public function index()
    {
        $userId = Auth::id();
        $pesanan = pesanans::where('user_id', $userId)
                    ->with('detailPesanans.menu')
                    ->orderByDesc('created_at')
                    ->paginate(10);

        return view('user.pesanan.index', compact('pesanan'));
    }

    // Form create (bisa dengan ?menu_id= untuk prefill)
    public function create(Request $request)
    {
        // Ambil semua menu yg stok > 0
        $menus = menus::where('stok', '>', 0)->orderBy('nama_menu')->get();
        $prefillMenuId = $request->query('menu_id');

        return view('user.pesanan.create', compact('menus', 'prefillMenuId'));
    }

    // Simpan pesanan
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|integer|exists:menus,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:1000'
        ], [
            'items.required' => 'Silakan pilih minimal 1 item menu.',
            'items.*.menu_id.exists' => 'Menu tidak tersedia.'
        ]);

        $userId = Auth::id();

        DB::beginTransaction();
        try {
            // buat header pesanan
            $pesanan = pesanans::create([
                'user_id' => $userId,
                'status' => 'pending',
                'catatan' => $request->input('catatan')
            ]);

            // insert detail_pesanans & update stok (opsional)
            foreach ($request->input('items') as $row) {
                $menuId = (int)$row['menu_id'];
                $jumlah = (int)$row['jumlah'];

                // cek stok sederhana
                $menu = menus::lockForUpdate()->find($menuId);
                if (!$menu) {
                    throw new \Exception("Menu ID {$menuId} tidak ditemukan.");
                }
                if ($menu->stok < $jumlah) {
                    throw new \Exception("Stok untuk menu '{$menu->nama_menu}' tidak cukup.");
                }

                // insert detail
                Detail_pesanans::create([
                    'pesanan_id' => $pesanan->id,
                    'menu_id' => $menuId,
                    'jumlah' => $jumlah
                ]);

                // kurangi stok (opsional, kalau mau track stok)
                $menu->stok = $menu->stok - $jumlah;
                $menu->save();
            }

            // log aktivitas
            Log_aktivitas::create([
                'user_id' => $userId,
                'aktivitas' => 'Membuat pesanan baru',
                'entity' => 'pesanan',
                'entity_id' => $pesanan->id
            ]);

            DB::commit();

            return redirect()->route('user.pesanan.show', $pesanan->id)
                ->with('success', 'Pesanan berhasil dibuat.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // Tampilkan detail pesanan
    public function show($id)
    {
        $pesanan = pesanans::with('detailPesanans.menu', 'user')->findOrFail($id);

        // pastikan milik user yg login (keamanan)
        if ($pesanan->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('user.pesanan.show', compact('pesanan'));
    }

    // Optional: batalkan pesanan (jika masih pending)
    public function destroy($id)
    {
        $pesanan = pesanans::findOrFail($id);
        if ($pesanan->user_id !== Auth::id()) abort(403);

        if (!in_array($pesanan->status, ['pending'])) {
            return back()->withErrors(['error' => 'Hanya pesanan dengan status pending yang bisa dibatalkan.']);
        }

        DB::transaction(function() use($pesanan) {
            // kembalikan stok
            foreach ($pesanan->detailPesanans as $d) {
                $menu = menus::find($d->menu_id);
                if ($menu) {
                    $menu->stok += $d->jumlah;
                    $menu->save();
                }
            }
            $pesanan->delete();

            Log_aktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Membatalkan pesanan',
                'entity' => 'pesanan',
                'entity_id' => $pesanan->id
            ]);
        });

        return redirect()->route('user.pesanan.index')->with('success', 'Pesanan dibatalkan.');
    }
}
