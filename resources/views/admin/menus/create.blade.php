{{-- resources/views/admin/menus/create.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Tambah Menu - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <h3>Tambah Menu</h3>

  {{-- Error Messages --}}
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
  <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
      <label class="form-label">Nama Menu</label>
      <input type="text" name="nama_menu" class="form-control" value="{{ old('nama_menu') }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Kategori</label>
      <select name="kategori_id" class="form-select" required>
        <option value="">-- Pilih Kategori --</option>
        @foreach($kategoris as $k)
          <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
            {{ $k->nama_kategori }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Deskripsi</label>
      <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Gambar</label>
      <input type="file" name="image" class="form-control" accept="image/*">
    </div>

    <div class="mb-3">
      <label class="form-label">Stok</label>
      <input type="number" name="stok" class="form-control" value="{{ old('stok', 0) }}" min="0" required>
    </div>

    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-success">Simpan</button>
      <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Batal</a>
    </div>
  </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
