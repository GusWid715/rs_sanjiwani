<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Detail Pesanan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <h3>Detail Pesanan #{{ $pesanan->id }}</h3>

  <table class="table table-bordered">
    <tr>
      <th>Menu</th>
      <td>{{ $pesanan->menu->nama_menu ?? '-' }}</td>
    </tr>
    <tr>
      <th>Jumlah</th>
      <td>{{ $pesanan->jumlah }}</td>
    </tr>
    <tr>
      <th>Alamat</th>
      <td>{{ $pesanan->alamat }}</td>
    </tr>
    <tr>
      <th>Ruangan</th>
      <td>{{ $pesanan->ruangan }} {{ $pesanan->no_ruangan }}</td>
    </tr>
    <tr>
      <th>Status</th>
      <td><span class="badge bg-info">{{ ucfirst($pesanan->status) }}</span></td>
    </tr>
  </table>

  <a href="{{ route('user.pesanan.index') }}" class="btn btn-secondary">Kembali</a>
</div>
</body>
</html>
