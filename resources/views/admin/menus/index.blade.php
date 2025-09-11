{{-- resources/views/admin/menus/index.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Manage Menus - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Manage Menus</h3>
    <div>
      <a href="{{ route('admin.menus.create') }}" class="btn btn-success">Tambah Menu</a>
      <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>
  </div>

  <form class="row g-2 mb-3" method="GET" action="{{ route('admin.menus.index') }}">
    <div class="col-md-4">
      <input type="text" name="q" class="form-control" placeholder="Cari nama atau deskripsi..." value="{{ $q ?? '' }}">
    </div>
    <div class="col-md-3">
      <select name="kategori" class="form-select">
        <option value="">Semua Kategori</option>
        @foreach($kategoris as $k)
          <option value="{{ $k->id }}" {{ (isset($kategori) && $kategori == $k->id) ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <button class="btn btn-secondary">Filter</button>
      <a href="{{ route('admin.menus.index') }}" class="btn btn-link">Reset</a>
    </div>
  </form>

  <div class="card">
    <div class="card-body p-0">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:60px">#</th>
            <th>Nama Menu</th>
            <th>Kategori</th>
            <th>Stok</th>
            <th style="width:200px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($menus as $m)
            <tr>
              <td>{{ $m->id }}</td>
              <td>{{ $m->nama_menu }}</td>
              <td>{{ $m->kategori->nama_kategori ?? '-' }}</td>
              <td>{{ $m->stok }}</td>
              <td>
                <a href="{{ route('admin.menus.show', $m->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                <a href="{{ route('admin.menus.edit', $m->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                <form action="{{ route('admin.menus.destroy', $m->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus menu ini?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center py-4">Belum ada menu.</td></tr>
          @endforelse
          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif
          @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    {{ $menus->withQueryString()->links() }}
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
