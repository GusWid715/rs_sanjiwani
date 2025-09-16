<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Laporan Pesanan - Gizi</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Laporan Pesanan</h3>
    <div>
      <a href="{{ route('gizi.dashboard') }}" class="btn btn-outline-secondary">Kembali Dashboard</a>
    </div>
  </div>

  <form class="row g-2 mb-3" method="GET" action="{{ route('gizi.laporan.index') }}">
    <div class="col-md-3">
      <label class="form-label">Mulai</label>
      <input type="date" name="start_date" class="form-control" value="{{ optional($start)->toDateString() }}">
    </div>
    <div class="col-md-3">
      <label class="form-label">Sampai</label>
      <input type="date" name="end_date" class="form-control" value="{{ optional($end)->toDateString() }}">
    </div>
    <div class="col-md-3">
      <label class="form-label">Status</label>
      <select name="status" class="form-select">
        <option value="">Semua</option>
        <option value="pending" {{ ($status ?? '') == 'pending' ? 'selected' : '' }}>pending</option>
        <option value="proses" {{ ($status ?? '') == 'proses' ? 'selected' : '' }}>proses</option>
        <option value="diterima" {{ ($status ?? '') == 'diterima' ? 'selected' : '' }}>diterima</option>
        <option value="ditolak" {{ ($status ?? '') == 'ditolak' ? 'selected' : '' }}>ditolak</option>
      </select>
    </div>
    <div class="col-md-3 align-self-end">
      <button class="btn btn-secondary">Filter</button>
      <a href="{{ route('gizi.laporan.export', request()->all()) }}" class="btn btn-outline-primary">Export CSV</a>
    </div>
  </form>

  <div class="row g-3 mb-3">
    <div class="col-md-3">
      <div class="card p-3">
        <h6>Total Pesanan</h6>
        <h3>{{ $total ?? 0 }}</h3>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-3">
        <h6>Pending</h6>
        <h3>{{ $byStatus['pending'] ?? 0 }}</h3>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-3">
        <h6>Proses</h6>
        <h3>{{ $byStatus['proses'] ?? 0 }}</h3>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-3">
        <h6>Diterima</h6>
        <h3>{{ $byStatus['diterima'] ?? 0 }}</h3>
      </div>
    </div>
  </div>

  <div class="card mb-3">
    <div class="card-body p-0">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th><th>User ID</th><th>Menu</th><th>Jumlah</th><th>Tanggal</th><th>Status</th><th>Ruangan</th><th>Catatan</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pesanans as $p)
            <tr>
              <td>{{ $p->id }}</td>
              <td>{{ $p->user_id }}</td>
              <td>
                @if(!empty($p->menu_id) && isset($menus[$p->menu_id]))
                  {{ $menus[$p->menu_id]->nama_menu ?? 'Menu #'.$p->menu_id }}
                @elseif(!empty($p->menu_id))
                  Menu #{{ $p->menu_id }}
                @else
                  -
                @endif
              </td>
              <td>{{ $p->jumlah ?? '-' }}</td>
              <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('Y-m-d') }}</td>
              <td>{{ $p->status }}</td>
              <td>{{ $p->ruangan ?? '-' }}</td>
              <td>{{ $p->catatan ?? '-' }}</td>
            </tr>
          @empty
            <tr><td colspan="8" class="text-center py-4">Tidak ada data.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <h5>Top Menu (Berdasarkan total jumlah)</h5>
      <table class="table table-sm">
        <thead><tr><th>Menu</th><th>Total Jumlah</th><th>Total Pesanan</th></tr></thead>
        <tbody>
          @forelse($totalsByMenu as $row)
            <tr>
              <td>{{ $menus[$row->menu_id]->nama_menu ?? 'Menu #'.$row->menu_id }}</td>
              <td>{{ $row->total_jumlah }}</td>
              <td>{{ $row->total_pesanan }}</td>
            </tr>
          @empty
            <tr><td colspan="3">Tidak ada.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
