@extends('layouts.app')
@section('title', 'Manajemen Paket Makanan')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Manajemen Paket</h3>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('manager.paket-makanan.create') }}"class="btn btn-primary">Tambah Paket Baru</a>
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
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Paket</th>
                            <th>Deskripsi</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paketMakanan as $paket)
                        <tr>
                            <td><strong>{{ $paket->nama_paket }}</strong></td>
                            <td>{{ $paket->deskripsi }}</td>
                            <td class="text-end">
                                <form action="{{ route('manager.paket-makanan.destroy', $paket->id) }}" method="POST" onsubmit="return confirm('Yakin hapus paket ini?');">
                                    <a href="{{ route('manager.paket-makanan.show', $paket->id) }}" class="btn btn-info btn-sm">Kelola Menu</a>
                                    <a href="{{ route('manager.paket-makanan.edit', $paket->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-4">Belum ada paket makanan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection