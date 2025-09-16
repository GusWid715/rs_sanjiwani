@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Tambah Menu Baru</h3>
        </div>
        <div class="card-body">
            {{-- Menampilkan Error Validasi --}}
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            {{-- Form Create --}}
            <form action="{{ route('gizi.menus.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="nama_menu" class="form-label"><strong>Nama Menu</strong></label>
                    <input type="text" name="nama_menu" id="nama_menu" class="form-control" value="{{ old('nama_menu') }}" required>
                </div>

                <div class="mb-3">
                    <label for="set_id" class="form-label"><strong>Set Makanan</strong></label>
                    {{-- Tambahkan 'disabled' jika $selectedSetId ada dari URL --}}
                    <select name="set_id" id="set_id" class="form-select" {{ isset($selectedSetId) ? 'disabled' : '' }} required>
                        <option value="">-- Pilih Set Makanan --</option>
                        @foreach($sets as $set)
                            <option value="{{ $set->id }}" {{ (old('set_id', $selectedSetId ?? '') == $set->id) ? 'selected' : '' }}>
                                {{ $set->nama_set }}
                            </option>
                        @endforeach
                    </select>
                    
                    {{-- 
                        Input tersembunyi ini PENTING.
                        Jika dropdown di-disable, input ini yang akan mengirimkan nilai set_id ke controller.
                    --}}
                    @if(isset($selectedSetId))
                        <input type="hidden" name="set_id" value="{{ $selectedSetId }}" />
                    @endif
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label"><strong>Deskripsi</strong></label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label"><strong>Gambar</strong></label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label for="stok" class="form-label"><strong>Stok</strong></label>
                    <input type="number" name="stok" id="stok" class="form-control" value="{{ old('stok', 0) }}" min="0" required>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Menu</button>
                    <a href="{{ route('gizi.sets.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection