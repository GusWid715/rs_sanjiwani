{{-- resources/views/User/pesanan/show.blade.php --}}
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Detail Pesanan #{{ $pesanan->id }} - RS Sanjiwani</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <h2>Detail Pesanan #{{ $pesanan->id }}</h2>

  <p><strong>Tanggal:</strong> {{ $pesanan->created_at ?? $pesanan->tanggal }}</p>
  <p><strong>Status:</strong> {{ ucfirst($pesanan->status) }}</p>
  <p><strong>Catatan:</strong> {{ $pesanan->catatan ?? '-' }}</p>

  <h4>Item</h4>
  <table class="table table-bordered">
    <thead><tr><th>Menu</th><th>Jumlah</th></tr></thead>
    <tbody>
      @foreach($pesanan->detailPesanans as $d)
        <tr>
          <td>{{ $d->menu->nama_menu ?? '—' }}</td>
          <td>{{ $d->jumlah }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <a href="{{ route('user.pesanan.index') }}" class="btn btn-secondary">Kembali</a>
</div>
</body>
</html>
