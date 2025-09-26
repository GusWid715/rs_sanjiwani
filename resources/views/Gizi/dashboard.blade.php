@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Header dan Tombol Navigasi Utama --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Dashboard Manager</h3>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('manager.pesanan.index') }}"class="btn btn-primary">Lihat Pesanan</a>
            <a href="{{ route('manager.paket.index') }}" class="btn btn-outline-secondary">Manajemen Paket</a>
            <a href="{{ route('manager.kategori.index') }}" class="btn btn-outline-secondary">Manajemen Kategori</a>
            <a href="{{ route('manager.menu.index') }}" class="btn btn-outline-secondary">Manajemen Menu</a>
            <a href="{{ route('manager.laporan.index') }}" class="btn btn-outline-secondary">Laporan</a>
            <a href="{{ route('manager.logs.index') }}" class="btn btn-outline-secondary">Log Aktivitas</a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="btn btn-danger">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
        </div>
    </div>

    {{-- Kartu Ringkasan Statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="card p-3">
                <h6>Pesanan Pending</h6>
                <h3>{{ $totalPesananPending }}</h3>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card p-3">
                <h6>Total Paket Makanan</h6>
                <h3>{{ $totalPaket }}</h3>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card p-3">
                <h6>Total Menu Keseluruhan</h6>
                <h3>{{ $totalMenu }}</h3>
            </div>
        </div>
    </div>

    {{-- Tabel Preview Pesanan Masuk --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Pesanan Masuk Terbaru (Pending)</strong>
            <a href="{{ route('manager.pesanan.index') }}" class="btn btn-outline-primary">Lihat semua</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ruang Rawat</th>
                            <th>Paket Makanan</th>
                            <th>Tanggal Pesan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPesanan as $pesanan)
                        <tr>
                            <td><strong>{{ $pesanan->ruangRawat->nama_ruang ?? 'N/A' }}</strong></td>
                            <td>{{ $pesanan->paketMakanan->nama_paket ?? 'N/A' }}</td>
                            <td>{{ $pesanan->tanggal->format('d M Y, H:i') }}</td>
                            <td><span class="badge bg-warning">{{ $pesanan->status }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">Tidak ada pesanan pending saat ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tabel Preview Log Aktivitas --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Log Aktivitas Terbaru</strong>
            <a href="{{ route('manager.logs.index') }}" class="btn btn-outline-primary">Lihat semua</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
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
                        @forelse($recentLogs as $log)
                        <tr>
                            <td><strong>{{ $log->user->name ?? 'Sistem' }}</strong></td>
                            <td>{{ $log->aktivitas }}</td>
                            <td>{{ $log->entity }}</td>
                            <td>{{ $log->entity_id }} </td>
                            <td>{{ $log->waktu }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-3">
                                Belum ada aktivitas yang tercatat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection