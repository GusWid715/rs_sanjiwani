@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Header dan Tombol Navigasi --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Dashboard Gizi</h3>
        <div>
            <a href="{{ route('gizi.pesanan.index') }}" class="btn btn-primary">Pesanan Masuk</a>
            <a href="{{ route('gizi.sets.index') }}" class="btn btn-outline-secondary">Manajemen Set</a>
            <a href="{{ route('gizi.laporan.index') }}" class="btn btn-outline-secondary">Laporan</a>
            <a href="{{ route('gizi.logs.index') }}" class="btn btn-outline-secondary">Log Aktivitas</a>
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
                <h6>Total Pending</h6>
                <h3>{{ $totalPending }}</h3>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card p-3">
                <h6>Total Selesai (Arsip)</h6>
                <h3>{{ $totalSelesai }}</h3>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card p-3">
                <h6>Jumlah Menu</h6>
                <h3>{{ $totalMenus }}</h3>
            </div>
        </div>
    </div>

    {{-- Tabel Preview Pesanan Masuk --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Pesanan Masuk (Pending) — 5 Teratas</strong>
            <a href="{{ route('gizi.pesanan.index') }}" class="btn btn-sm btn-link">Lihat semua</a>
        </div>
        <div class="card-body p-0">
            {{-- =================== PERUBAHAN DIMULAI DI SINI =================== --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Pasien</th>
                            <th>Menu</th>
                            <th>Set Makanan</th>
                            <th class="text-center">Jumlah</th>
                            <th>Ruangan</th>
                            <th>Waktu Pesan</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Menggunakan variabel $incomingPesanans dari DashboardController --}}
                        @forelse($incomingPesanans as $pesanan)
                        <tr>
                            <td><strong>{{ $pesanan->user->name ?? 'N/A' }}</strong></td>
                            <td>{{ $pesanan->menu->nama_menu ?? 'N/A' }}</td>
                            <td>
                                <span class="text">{{ $pesanan->menu->set->nama_set ?? 'Tanpa Set' }}</span>
                            </td>
                            <td class="text-center">{{ $pesanan->jumlah }}</td>
                            <td>{{ $pesanan->ruangan }}</td>
                            <td>{{ \Carbon\Carbon::parse($pesanan->tanggal)->format('d M Y, H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            {{-- Colspan diubah menjadi 6 karena tidak ada kolom Aksi --}}
                            <td colspan="6" class="text-center py-4">
                                Tidak ada pesanan pending saat ini.
                            </td>
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
            <a href="{{ route('gizi.logs.index') }}" class="btn btn-sm btn-link">Lihat semua</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Aktivitas</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLogs as $log)
                        <tr>
                            <td>{{ $log->user->name ?? '-' }}</td>
                            <td>{{ $log->aktivitas }}</td>
                            <td>{{ \Carbon\Carbon::parse($log->waktu)->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-3">Belum ada aktivitas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection