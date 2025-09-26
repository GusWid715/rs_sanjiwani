@extends('layouts.app')

@section('title', 'Manajemen Menu')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Manajemen Menu</h3>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('manager.menu.create') }}"class="btn btn-primary">Tambah Menu Baru</a>
            <a href="{{ route('manager.dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
        </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="card">
        <div class="card-body">
            {{-- Form Filter --}}
            <form method="GET" action="{{ route('manager.menu.index') }}" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="q" class="form-label">Cari Nama Menu</label>
                    <input type="text" name="q" id="q" class="form-control" value="{{ $q ?? '' }}">
                </div>
                <div class="col-md-5">
                    <label for="kategori_id" class="form-label">Filter Kategori</label>
                    <select name="kategori_id" id="kategori_id" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoriMenu as $kategori)
                        <option value="{{ $kategori->id }}" {{ ($kategori_id ?? '') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr class="text-secondary">
                            <th>Nama Menu</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($menu as $item)
                        <tr>
                            <td><strong>{{ $item->nama_menu }}</strong></td>
                            <td>{{ $item->kategoriMenu->nama_kategori ?? 'N/A' }}</td>
                            <td>{{ $item->stok }}</td>
                            <td class="text-end">
                                <form action="{{ route('manager.menu.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus menu ini?');">
                                    <a href="{{ route('manager.menu.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4">Data menu tidak ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection