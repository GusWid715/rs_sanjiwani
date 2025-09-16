<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Edit Pesanan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <h3>Edit Pesanan</h3>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <form action="{{ route('user.pesanan.update', $pesanan->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label class="form-label">Menu</label>
      <select name="menu_id" class="form-select" required>
        @foreach($menus as $m)
          <option value="{{ $m->id }}" {{ $pesanan->menu_id == $m->id ? 'selected' : '' }}>
            {{ $m->nama_menu }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Jumlah</label>
      <input type="number" name="jumlah" class="form-control" value="{{ $pesanan->jumlah }}" min="1" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Alamat</label>
      <input type="text" name="alamat" class="form-control" value="{{ $pesanan->alamat }}" required>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Ruangan</label>
        <input type="text" name="ruangan" class="form-control" value="{{ $pesanan->ruangan }}">
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">No Ruangan</label>
        <input type="text" name="no_ruangan" class="form-control" value="{{ $pesanan->no_ruangan }}">
      </div>
    </div>

    <div class="d-flex gap-2">
      <button class="btn btn-primary">Update</button>
      <a href="{{ route('user.pesanan.index') }}" class="btn btn-secondary">Batal</a>
    </div>
  </form>
</div>
</body>
</html>
