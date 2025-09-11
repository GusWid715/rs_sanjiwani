{{-- resources/views/admin/users/show.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Detail User - Admin | RS Sanjiwani</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Detail User: {{ $user->username }}</h3>
    <div>
      <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Kembali</a>
      <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">Edit</a>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <p><strong>ID:</strong> {{ $user->id }}</p>
      <p><strong>Username:</strong> {{ $user->username }}</p>
      <p><strong>Nama Lengkap:</strong> {{ $user->nama_lengkap ?? '-' }}</p>
      <p><strong>Role:</strong> <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role==='manager' ? 'info' : 'secondary') }}">{{ ucfirst($user->role) }}</span></p>
      <p><strong>Dibuat:</strong> {{ $user->created_at ? $user->created_at->format('Y-m-d H:i') : '-' }}</p>
      <p><strong>Terakhir diupdate:</strong> {{ $user->updated_at ? $user->updated_at->format('Y-m-d H:i') : '-' }}</p>
    </div>
  </div>

  {{-- Hapus --}}
  <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="mt-3" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
    @csrf
    @method('DELETE')
    <button class="btn btn-danger">Hapus User</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
