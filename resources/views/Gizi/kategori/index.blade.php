@extends('layouts.app')

@section('title', 'Manajemen Kategori Menu')

@section('content')
<div class="container">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Manajemen kategori</h3>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('manager.kategori-menu.create') }}"class="btn btn-primary">Tambah Paket Baru</a>
            <a href="{{ route('manager.dashboard') }}" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Kategori</th>
                            <th>Tanggal Dibuat</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategoriMenu as $kategori)
                        <tr>
                            <td><strong>{{ $kategori->nama_kategori }}</strong></td>
                            <td>{{ $kategori->created_at->format('d M Y') }}</td>
                            <td class="text-end">
                                <form action="{{ route('manager.kategori-menu.destroy', $kategori->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                    <a href="{{ route('manager.kategori-menu.edit', $kategori->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">Belum ada data kategori menu.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($kategoriMenu->hasPages())
        <div class="card-footer">
            {{ $kategoriMenu->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection