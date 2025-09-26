@extends('layouts.app')
@section('title', 'Edit Paket Makanan')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Form Edit Paket Makanan</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('manager.paket-makanan.update', $paketMakanan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nama_paket" class="form-label">Nama Paket</label>
                    <input type="text" name="nama_paket" id="nama_paket" class="form-control" value="{{ old('nama_paket', $paketMakanan->nama_paket) }}" required>
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $paketMakanan->deskripsi) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('manager.paket-makanan.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection