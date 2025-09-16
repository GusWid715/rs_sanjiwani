<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Pesanan Saya</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <h3>Pesanan Saya</h3>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <a href="{{ route('user.pesanan.create') }}" class="btn btn-primary mb-3">Buat Pesanan Baru</a>

  <table class="table table-bordered table-hover">
    <thead class="table-light">
      <tr>
        <th>#</th>
        <th>Menu</th>
        <th>Jumlah</th>
        <th>Alamat</th>
        <th>Ruangan</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($pesanans as $p)
        <tr>
          <td>{{ $p->id }}</td>
          <td>{{ $p->menu->nama_menu ?? '-' }}</td>
          <td>{{ $p->jumlah }}</td>
          <td>{{ $p->alamat }}</td>
          <td>{{ $p->ruangan }} {{ $p->no_ruangan }}</td>
          <td><span class="badge bg-info">{{ ucfirst($p->status) }}</span></td>
          <td>
            <a href="{{ route('user.pesanan.show', $p->id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
            <a href="{{ route('user.pesanan.edit', $p->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
            <form action="{{ route('user.pesanan.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pesanan ini?')">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Hapus</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" class="text-center">Belum ada pesanan.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="mt-3">
    {{ $pesanans->links() }}
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
