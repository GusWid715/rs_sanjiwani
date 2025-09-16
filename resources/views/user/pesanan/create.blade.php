<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Buat Pesanan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <h3>Buat Pesanan Baru</h3>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

<form action="{{ route('user.pesanan.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">Pilih Menu</label>
        <select name="menu_id" class="form-select" required>
            <option value="">-- Pilih Menu --</option>
            @foreach($menus as $menu)
                <option value="{{ $menu->id }}" 
                    {{ $selectedMenu && $selectedMenu->id == $menu->id ? 'selected' : '' }}>
                    {{ $menu->nama_menu }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Jumlah</label>
      <input type="number" name="jumlah" class="form-control" min="1" value="1" required>
    </div>

      <div class="mb-3">
        <label class="form-label">Ruangan</label>
        <input type="text" name="ruangan" class="form-control" min="1" value="1" required>
      </div>

    <div class="d-flex gap-2">
      <button class="btn btn-success">Simpan</button>
      <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">Batal</a>
    </div>
  </form>
</div>
</body>
</html>
