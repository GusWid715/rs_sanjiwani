<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Pesanan Masuk - Gizi</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Pesanan Masuk</h3>
    <a href="{{ route('gizi.dashboard') }}" class="btn btn-outline-secondary">Kembali ke dashboard</a>
  </div>

  <div class="card">
    <div class="card-body p-0">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>User ID</th>
            <th>Menu ID</th>
            <th>Jumlah</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Ruangan</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pesanans as $p)
            <tr>
              <td>{{ $p->id }}</td>
              <td>{{ $p->user_id }}</td>
              <td>{{ $p->menu_id ?? '-' }}</td>
              <td>{{ $p->jumlah ?? '-' }}</td>
              <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('Y-m-d') }}</td>
              <td>{{ $p->status }}</td>
              <td>{{ $p->ruangan ?? '-' }}</td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center py-4">Belum ada pesanan.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
