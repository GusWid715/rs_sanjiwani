@extends('layouts.app')
@section('title', 'Rakit Paket: ' . $paketMakanan->nama_paket)

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Kelola Menu untuk Paket: <strong>{{ $paketMakanan->nama_paket }}</strong></h3>
        <a href="{{ route('manager.paket.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    <div class="row g-4">
        {{-- Kolom Kiri: Menu dalam Paket --}}
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Menu dalam Paket Ini ({{ $paketMakanan->menu->count() }})</h5>
                </div>
                <div class="card-body">
                    @forelse($paketMakanan->menu as $menu)
                    <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                        <span>{{ $menu->nama_menu }}</span>
                        <form action="{{ route('manager.paket-makanan.detachMenu', $paketMakanan->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                    @empty
                    <p class="text-muted">Belum ada menu di dalam paket ini.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Daftar Menu Tersedia --}}
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Daftar Menu Tersedia</h5>
                </div>
                <div class="card-body">
                    {{-- Filter Berdasarkan Kategori --}}
                    <form method="GET" action="{{ route('manager.paket-makanan.show', $paketMakanan->id) }}" class="mb-3">
                        <div class="input-group">
                            <select name="kategori_id" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoriMenu as $kategori)
                                <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                                @endforeach
                            </select>
                            <button class="btn btn-outline-secondary" type="submit">Filter</button>
                        </div>
                    </form>

                    {{-- Daftar Menu untuk Ditambahkan --}}
                    @forelse($menuTersedia as $menu)
                    <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                        <span>{{ $menu->nama_menu }} <small class="text-muted">({{ $menu->kategoriMenu->nama_kategori }})</small></span>
                        <form action="{{ route('manager.paket-makanan.attachMenu', $paketMakanan->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                            <button type="submit" class="btn btn-primary btn-sm">Tambah</button>
                        </form>
                    </div>
                    @empty
                    <p class="text-muted">Tidak ada menu lagi yang tersedia atau sesuai filter.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection