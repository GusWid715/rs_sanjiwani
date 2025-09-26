@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
<div class="container">
    {{-- Header Halaman --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Log Aktivitas</h3>
        <a href="{{ route('manager.dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
    </div>

    {{-- Form Filter --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('manager.logs.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="q" class="form-label">Pencarian</label>
                    <input type="text" name="q" id="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Cari aktivitas...">
                </div>
                <div class="col-md-3">
                    <label for="user_id" class="form-label">Pengguna</label>
                    <select name="user_id" id="user_id" class="form-select">
                        <option value="">Semua Pengguna</option>
                        @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ (isset($user_id) && $user_id == $u->id) ? 'selected' : '' }}>
                            {{ $u->name ?? 'User '.$u->id }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="from" class="form-label">Dari Tanggal</label>
                    <input type="date" name="from" id="from" value="{{ $from ?? '' }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label for="to" class="form-label">Sampai Tanggal</label>
                    <input type="date" name="to" id="to" value="{{ $to ?? '' }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Log Aktivitas --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            {{-- Header tabel --}}
                            <th style="width: 20%;">User</th>
                            <th style="width: 25%;">Aktivitas</th>
                            <th style="width: 20%;">Entity</th>
                            <th style="width: 20%;">Entity Id</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td><strong>{{ $log->user->name ?? 'Sistem' }}</strong></td>
                            <td>{{ $log->aktivitas }}</td>
                            <td>{{ $log->entity }}</td>
                            <td>{{ $log->entity_id }} </td>
                            <td>{{ $log->waktu }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">Belum ada log aktivitas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
