{{-- resources/views/admin/menus/show.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Detail Menu - Admin - RS Sanjiwani</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Detail Menu</h3>
    <div>
      <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-secondary">Kembali</a>
      <a href="{{ route('admin.menus.edit', $menu->id) }}" class="btn btn-warning">Edit</a>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="row">
        <div class="col-md-4">
          @if($menu->image)
            <img src="{{ asset('images/' . $menu->image) }}" alt="{{ $menu->nama_menu }}" class="img-fluid rounded border">
          @else
            <div class="text-muted fst-italic">Tidak ada gambar</div>
          @endif
        </div>
        <div class="col-md-8">
          <table class="table table-borderless">
            <tr>
              <th style="width:200px">Nama Menu</th>
              <td>{{ $menu->nama_menu }}</td>
            </tr>
            <tr>
            <th>Kategori</th>
              <td>{{ $menu->kategori->nama_kategori ?? '-' }}</td>
            </tr>
            <tr>
              <th>Dibuat pada</th>
              <td>{{ $menu->created_at ? $menu->created_at->format('d M Y H:i') : '-' }}</td>
            </tr>
            <tr>
              <th>Diperbarui pada</th>
              <td>{{ $menu->updated_at ? $menu->updated_at->format('d M Y H:i') : '-' }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
