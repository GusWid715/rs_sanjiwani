@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{-- Menggunakan Flexbox untuk menata judul dan tombol --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Detail Set: <strong>{{ $set->nama_set }}</strong></h2>
                        
                        {{-- Tombol Kembali di Sebelah Kanan --}}
                        <a href="{{ route('gizi.sets.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Set
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <p class="lead">{{ $set->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                     {{-- Menggunakan Flexbox lagi di sini --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Menu dalam Set Ini</h3>

                        {{-- Tombol Tambah Menu di Sebelah Kanan --}}
                        <a href="{{ route('gizi.menus.create', ['set_id' => $set->id]) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Menu Baru
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Gambar</th>
                                    <th>Nama Menu</th>
                                    <th>Deskripsi</th>
                                    <th>Stok</th>
                                    <th style="width: 200px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($set->menus as $menu)
                                    <tr>
                                        <td class="text-center">
                                            @if($menu->image)
                                                <img src="{{ asset('images/' . $menu->image) }}" alt="{{ $menu->nama_menu }}" width="100" class="img-thumbnail">
                                            @else
                                                <span>Tidak ada gambar</span>
                                            @endif
                                        </td>
                                        <td>{{ $menu->nama_menu }}</td>
                                        <td>{{ $menu->deskripsi }}</td>
                                        <td>{{ $menu->stok }}</td>
                                        <td>
                                            <form action="{{ route('gizi.menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu ini?');">
                                                <a href="{{ route('gizi.menus.show', $menu->id) }}" class="btn btn-sm btn-info">Detail</a>
                                                <a href="{{ route('gizi.menus.edit', $menu->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <p class="my-3">Belum ada menu yang terdaftar dalam set ini.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection