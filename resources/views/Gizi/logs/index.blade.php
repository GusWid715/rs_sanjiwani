{{-- resources/views/gizi/logs/index.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Log Aktivitas - Gizi</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Log Aktivitas</h3>
    <a href="{{ route('gizi.dashboard') }}" class="btn btn-outline-secondary">Kembali Dashboard</a>
  </div>

  {{-- Filter --}}
  <form class="row g-2 mb-3" method="GET" action="{{ route('gizi.logs.index') }}">
    <div class="col-md-4">
      <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Cari aktivitas / entity ...">
    </div>
    <div class="col-md-3">
      <select name="user_id" class="form-select">
        <option value="">Semua Pengguna</option>
        @foreach($users as $u)
          <option value="{{ $u->id }}" {{ (isset($user_id) && $user_id == $u->id) ? 'selected' : '' }}>
            {{ $u->name ?? $u->username ?? 'User '.$u->id }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <input type="date" name="from" value="{{ $from ?? '' }}" class="form-control" placeholder="From">
    </div>
    <div class="col-md-2">
      <input type="date" name="to" value="{{ $to ?? '' }}" class="form-control" placeholder="To">
    </div>
    <div class="col-md-1">
      <button class="btn btn-secondary w-100">Filter</button>
    </div>
  </form>

  {{-- Tabel --}}
  <div class="card">
    <div class="card-body p-0">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:60px">#</th>
            <th>Waktu</th>
            <th>Pengguna</th>
            <th>Aktivitas</th>
            <th>Entity</th>
            <th>Entity ID</th>
          </tr>
        </thead>
        <tbody>
          @forelse($logs as $log)
            <tr>
              <td>{{ $log->id }}</td>
              <td>{{ $log->waktu ? \Carbon\Carbon::parse($log->waktu)->format('Y-m-d H:i:s') : '-' }}</td>
              <td>{{ $log->user->name ?? $log->user->username ?? ('User '.$log->user_id) }}</td>
              <td>{{ $log->aktivitas }}</td>
              <td>{{ $log->entity }}</td>
              <td>{{ $log->entity_id }}</td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center py-4">Belum ada log aktivitas.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    {{ $logs->links() }}
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
