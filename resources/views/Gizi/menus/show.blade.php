@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Detail Menu</h3>
        <div>
            <a href="{{ route('gizi.menus.edit', $menu->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Menu
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    @if($menu->image)
                    {{-- BARIS INI SUDAH DIPERBAIKI --}}
                    <img src="{{ asset('images/' . $menu->image) }}" alt="{{ $menu->nama_menu }}" class="img-fluid rounded border">
                    @else
                    <div class="text-center p-5 border rounded bg-light">
                        <span class="text-muted fst-italic">Tidak ada gambar</span>
                    </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <h2 class="mb-3">{{ $menu->nama_menu }}</h2>
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 200px;">Set Makanan</th>
                            <td>
                                <span class="badge bg-primary fs-6">{{ $menu->set->nama_set ?? '-' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Stok Tersedia</th>
                            <td>{{ $menu->stok }} porsi</td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>{{ $menu->deskripsi ?? 'Tidak ada deskripsi.' }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat pada</th>
                            <td>{{ $menu->created_at ? $menu->created_at->format('d M Y, H:i') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Diperbarui pada</th>
                            <td>{{ $menu->updated_at ? $menu->updated_at->format('d M Y, H:i') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection