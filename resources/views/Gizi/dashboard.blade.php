{{-- resources/views/gizi/dashboard.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Dashboard Gizi - RS Sanjiwani</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card-stat { min-height: 100px; }
    .img-menu { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; }
    .table-wrap { max-height: 420px; overflow:auto; }
  </style>
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Dashboard Gizi</h3>
    <div>
      <a href="{{ route('gizi.pesanan.index') }}" class="btn btn-primary">Pesanan Masuk</a>
      
      {{-- TOMBOL BARU DITAMBAHKAN DI SINI --}}
      <a href="{{ route('gizi.sets.index') }}" class="btn btn-outline-secondary">Manajemen Set</a>

      <a href="{{ route('gizi.laporan.index') }}" class="btn btn-outline-secondary">Laporan</a>
      <a href="{{ route('gizi.logs.index') }}" class="btn btn-outline-secondary">Log Aktivitas</a>
      <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="btn btn-danger">Logout</a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
      </form>
    </div>
  </div>

  {{-- Kartu ringkasan --}}
  <div class="row g-3 mb-4">
    <div class="col-sm-3">
      <div class="card card-stat p-3">
        <div class="d-flex justify-content-between">
          <div>
            <h6>Total Pending</h6>
            <h3>{{ $totalPending }}</h3>
          </div>
          <div class="align-self-center">
            <span class="badge bg-warning">Pending</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-3">
      <div class="card card-stat p-3">
        <div class="d-flex justify-content-between">
          <div>
            <h6>Total Proses</h6>
            <h3>{{ $totalProses }}</h3>
          </div>
          <div class="align-self-center">
            <span class="badge bg-info">Proses</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-3">
      <div class="card card-stat p-3">
        <div class="d-flex justify-content-between">
          <div>
            <h6>Total Selesai</h6>
            <h3>{{ $totalSelesai }}</h3>
          </div>
          <div class="align-self-center">
            <span class="badge bg-success">Selesai</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-3">
      <div class="card card-stat p-3">
        <div class="d-flex justify-content-between">
          <div>
            <h6>Jumlah Menu</h6>
            <h3>{{ $totalMenus }}</h3>
          </div>
          <div class="align-self-center">
            <span class="badge bg-secondary">Menu</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Pesanan masuk (preview) --}}
  <div class="card mb-4">
    <div class="card-header">
      <strong>Pesanan Masuk (Pending) — preview</strong>
      <a href="{{ route('gizi.pesanan.index') }}" class="btn btn-sm btn-link float-end">Lihat semua</a>
    </div>
    <div class="card-body table-wrap p-0">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Pasien</th>
            <th>Menu</th>
            <th>Jumlah</th>
            <th>Ruangan</th>
            <th>Tanggal</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($incomingPesanans as $p)
            <tr>
              <td>{{ $p->id }}</td>
              <td>{{ $p->user->name ?? '—' }}</td>
              <td>
                @if($p->menu?->image)
                  <img src="{{ asset('images/' . $p->menu->image) }}" alt="" class="img-menu me-2">
                @endif
                {{ $p->menu->nama_menu ?? '-' }}
              </td>
              <td>{{ $p->jumlah ?? '-' }}</td>
              <td>{{ $p->ruangan ?? '-' }}</td>
              <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('Y-m-d H:i') }}</td>
              <td>
                <a href="{{ route('gizi.pesanan.index') }}?view={{ $p->id }}" class="btn btn-sm btn-outline-primary">Detail</a>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center py-4">Tidak ada pesanan pending.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Log aktivitas --}}
  <div class="card">
    <div class="card-header">
      <strong>Log Aktivitas Terbaru</strong>
      <a href="{{ route('gizi.logs.index') }}" class="btn btn-sm btn-link float-end">Lihat semua</a>
    </div>
    <div class="card-body p-0">
      <table class="table table-sm mb-0">
        <thead class="table-light">
          <tr><th>#</th><th>User</th><th>Aktivitas</th><th>Entity</th><th>Waktu</th></tr>
        </thead>
        <tbody>
          @forelse($recentLogs as $log)
            <tr>
              <td>{{ $log->id }}</td>
              <td>{{ $log->user->name ?? '-' }}</td>
              <td>{{ $log->aktivitas }}</td>
              <td>{{ $log->entity }}#{{ $log->entity_id }}</td>
              <td>{{ \Carbon\Carbon::parse($log->waktu)->format('Y-m-d H:i') }}</td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center py-3">Belum ada log.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>