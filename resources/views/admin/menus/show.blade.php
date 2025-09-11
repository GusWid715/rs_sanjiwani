{{-- resources/views/admin/menus/show.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Detail Menu - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <h3>Detail Menu</h3>

  <div class="card">
    <div class="card-body">
      <p><strong>ID:</strong> {{ $menu->id }}</p>
      <p><strong>Nama Menu:</strong> {{ $menu->nama_menu }}</p>
      <p><strong>Kategori:</strong> {{ $menu->kategori->nama_kategori ?? '-' }}</p>
      <p><strong>Deskripsi:</strong> {{ $menu->deskripsi ?? '-' }}</p>
      <p><strong>Stok:</strong> {{ $menu->stok }}</p>
    </div>
  </div>

  <div class="mt-3">
    <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Kembali</a>
    <a href="{{ route('admin.menus.edit', $menu->id) }}" class="btn btn-warning">Edit</a>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
