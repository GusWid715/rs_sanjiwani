{{-- resources/views/admin/dashboard.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard - RS Sanjiwani</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card-stats { min-height: 120px; }
    .recent-log { font-size: 0.95rem; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Admin - RS Sanjiwani</a>
    <div class="ms-auto d-flex align-items-center">
      <span class="me-3">Hi, {{ auth()->user()->nama_lengkap ?? auth()->user()->username }}</span>
      <form action="{{ route('logout') }}" method="POST" class="m-0">
        @csrf
        <button class="btn btn-outline-danger btn-sm">Logout</button>
      </form>
    </div>
  </div>
</nav>

<div class="container py-4">
  <div class="row mb-4">
    <div class="col-md-8">
      <h3>Dashboard Admin</h3>
      <p class="text-muted">Kelola user & menu makanan dari sini.</p>
    </div>
    <div class="col-md-4 text-end align-self-center">
      <a href="{{ route('admin.users.index') }}" class="btn btn-primary me-2">Manage Users</a>
      <a href="{{ route('admin.menus.index') }}" class="btn btn-success">Manage Menus</a>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-3">
      <div class="card card-stats">
        <div class="card-body">
          <h6 class="text-muted">Total Users</h6>
          <h3>{{ $totalUsers }}</h3>
          <small class="text-muted">Admins: {{ $totalAdmins }} — Managers: {{ $totalManagers }} — Pasien: {{ $totalPasien }}</small>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-md-3">
      <div class="card card-stats">
        <div class="card-body">
          <h6 class="text-muted">Total Menu</h6>
          <h3>{{ $totalMenus }}</h3>
          <small class="text-muted">Menu aktif di sistem</small>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-body">
          <h6>Recent Activity</h6>
          @if($recentLogs->isEmpty())
            <p class="text-muted">Belum ada aktivitas.</p>
          @else
            <ul class="list-unstyled recent-log">
              @foreach($recentLogs as $log)
                <li class="mb-2">
                  <strong>{{ $log->aktivitas }}</strong>
                  <div class="text-muted small">
                    by {{ $log->user ? ($log->user->nama_lengkap ?? $log->user->username) : 'System' }}
                    — {{ $log->waktu }}
                  </div>
                </li>
              @endforeach
            </ul>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-3">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <h6>Quick Links</h6>
          <div class="d-flex gap-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">Manage Users</a>
            <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-success">Manage Menus</a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-outline-secondary">Create Admin / Manager</a>
          </div>
          <p class="mt-2 text-muted small">Catatan: Pembuatan akun pasien dilakukan melalui pendaftaran (register).</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- bootstrap bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>