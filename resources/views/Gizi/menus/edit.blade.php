@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Edit Menu: <strong>{{ $menu->nama_menu }}</strong></h3>
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

            {{-- Form Update --}}
            <form action="{{ route('gizi.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nama_menu" class="form-label"><strong>Nama Menu</strong></label>
                    <input type="text" name="nama_menu" id="nama_menu" class="form-control" value="{{ old('nama_menu', $menu->nama_menu) }}" required>
                </div>

                <div class="mb-3">
                    <label for="set_id" class="form-label"><strong>Set Makanan</strong></label>
                    <select name="set_id" id="set_id" class="form-select" required>
                        @foreach($sets as $set)
                            {{-- 
                                Memilih set yang sesuai dengan data menu saat ini,
                                atau dari old input jika validasi gagal.
                            --}}
                            <option value="{{ $set->id }}" {{ old('set_id', $menu->set_id) == $set->id ? 'selected' : '' }}>
                                {{ $set->nama_set }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label"><strong>Deskripsi</strong></label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label"><strong>Ganti Gambar</strong></label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                    @if($menu->image)
                    <div class="mt-2">
                        <small>Gambar saat ini:</small><br>
                        <img src="{{ asset('images/' . $menu->image) }}" alt="Preview" width="120" class="img-thumbnail">
                    </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="stok" class="form-label"><strong>Stok</strong></label>
                    <input type="number" name="stok" id="stok" class="form-control" min="0" value="{{ old('stok', $menu->stok) }}" required>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Update Menu</button>
                    <a href="{{ route('gizi.sets.show', $menu->set_id) }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection