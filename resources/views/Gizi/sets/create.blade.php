@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Tambah Set Makanan Baru</h2>
                </div>

                <div class="card-body">
                    {{-- Menampilkan error validasi jika ada --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> Terjadi beberapa masalah dengan input Anda.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form untuk membuat set baru --}}
                    <form action="{{ route('gizi.sets.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label for="nama_set"><strong>Nama Set:</strong></label>
                                    <input type="text" name="nama_set" id="nama_set" class="form-control @error('nama_set') is-invalid @enderror" placeholder="Contoh: Set Vitamin A" value="{{ old('nama_set') }}" required>
                                    
                                    {{-- Pesan error untuk field nama_set --}}
                                    @error('nama_set')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 mt-3">
                                <div class="form-group">
                                    <label for="deskripsi"><strong>Deskripsi:</strong></label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" style="height:150px" name="deskripsi" id="deskripsi" placeholder="Jelaskan secara singkat mengenai set makanan ini">{{ old('deskripsi') }}</textarea>
                                    
                                    {{-- Pesan error untuk field deskripsi --}}
                                    @error('deskripsi')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-4">
                                <a href="{{ route('gizi.sets.index') }}" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection