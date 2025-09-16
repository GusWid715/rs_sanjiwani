@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{-- Menggunakan Flexbox untuk menata judul dan tombol --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Manajemen Set Makanan</h2>
                        
                        {{-- Grup Tombol di Sebelah Kanan --}}
                        <div class="d-flex gap-2">
                            <a href="{{ route('gizi.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                            </a>
                            <a href="{{ route('gizi.sets.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Set Baru
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Menampilkan pesan sukses atau error dari session --}}
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success" role="alert">
                            {{ $message }}
                        </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ $message }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" style="width: 5%;">No</th>
                                    <th scope="col">Nama Set</th>
                                    <th scope="col">Deskripsi</th>
                                    <th scope="col" style="width: 25%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sets as $key => $set)
                                    <tr>
                                        <td>{{ $sets->firstItem() + $key }}</td>
                                        <td>{{ $set->nama_set }}</td>
                                        <td>{{ $set->deskripsi ?? 'Tidak ada deskripsi' }}</td>
                                        <td>
                                            <form action="{{ route('gizi.sets.destroy', $set->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus set ini?');">
                                                
                                                <a href="{{ route('gizi.sets.show', $set->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> Kelola Menu
                                                </a>

                                                <a href="{{ route('gizi.sets.edit', $set->id) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>

                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada data set makanan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Link Paginasi --}}
                    <div class="d-flex justify-content-center">
                        {!! $sets->links() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection