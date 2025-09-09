{{-- resources/views/User/pesanan/index.blade.php --}}
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Daftar Pesanan - RS Sanjiwani</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <h2>Daftar Pesanan Saya</h2>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if($pesanan->isEmpty())
    <div class="alert alert-info">Belum ada pesanan.</div>
  @else
    <table class="table table-striped">
      <thead><tr><th>#</th><th>Tanggal</th><th>Status</th><th>Catatan</th><th>Aksi</th></tr></thead>
      <tbody>
      @foreach($pesanan as $p)
        <tr>
          <td>{{ $p->id }}</td>
          <td>{{ $p->created_at ?? $p->tanggal }}</td>
          <td>{{ ucfirst($p->status) }}</td>
          <td>{{ \Illuminate\Support\Str::limit($p->catatan, 50) }}</td>
          <td>
            <a href="{{ route('user.pesanan.show', $p->id) }}" class="btn btn-sm btn-primary">Lihat</a>
            @if($p->status === 'pending')
              <form action="{{ route('user.pesanan.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Batalkan pesanan?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger">Batal</button>
              </form>
            @endif
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>

    {{ $pesanan->links() }}
  @endif

  <a href="{{ route('user.pesanan.create') }}" class="btn btn-success mt-3">Buat Pesanan Baru</a>
</div>
</body>
</html>
