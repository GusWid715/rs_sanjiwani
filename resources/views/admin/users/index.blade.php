{{-- resources/views/admin/users/index.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Manage Users - Admin - RS Sanjiwani</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .table-actions > form { display:inline-block; }
    .search-row { gap: .5rem; }
  </style>
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Manage Users</h3>
    <div>
      <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Buat Akun Admin / Manager</a>
      <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Kembali Dashboard</a>
    </div>
  </div>

  {{-- Search & Filter --}}
  <form class="row row-cols-lg-auto align-items-center mb-3 search-row" method="GET" action="{{ route('admin.users.index') }}">
    <div class="col-12">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari username atau nama...">
    </div>
    <div class="col-12">
      <select name="role" class="form-select">
        <option value="">Semua Role</option>
        <option value="admin" {{ request('role')=='admin' ? 'selected' : '' }}>Admin</option>
        <option value="manager" {{ request('role')=='manager' ? 'selected' : '' }}>Manager</option>
        <option value="pasien" {{ request('role')=='pasien' ? 'selected' : '' }}>Pasien</option>
      </select>
    </div>
    <div class="col-12">
      <button class="btn btn-secondary" type="submit">Filter</button>
      <a href="{{ route('admin.users.index') }}" class="btn btn-link">Reset</a>
    </div>
  </form>

  {{-- Table --}}
  <div class="card">
    <div class="card-body p-0">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:60px">#</th>
            <th>Username</th>
            <th>Nama Lengkap</th>
            <th style="width:140px">Role</th>
            <th style="width:180px">Dibuat</th>
            <th style="width:180px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $user)
            <tr>
              <td>{{ $user->id }}</td>
              <td>{{ $user->username }}</td>
              <td>{{ $user->nama_lengkap ?? '-' }}</td>
              <td><span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role==='manager' ? 'info' : 'secondary') }}">{{ ucfirst($user->role) }}</span></td>
              <td>{{ $user->created_at ? $user->created_at->format('Y-m-d H:i') : '-' }}</td>
              <td class="table-actions">
                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>

                {{-- Delete form --}}
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center py-4">Belum ada user.</td>
            </tr>
          @endforelse
          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif
          @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif
        </tbody>
      </table>
    </div>
  </div>

  {{-- Pagination --}}
  <div class="mt-3">
    {{ $users->withQueryString()->links() }}
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
