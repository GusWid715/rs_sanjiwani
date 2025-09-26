@extends('layouts.app')

@section('title', 'Laporan Pesanan')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Laporan</h3>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('manager.dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
        </div>
    </div>
    {{-- Form untuk filter laporan --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('manager.laporan.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" name="start_date" id="start_date" value="{{ optional($start)->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" name="end_date" id="end_date" value="{{ optional($end)->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="proses" {{ $status == 'proses' ? 'selected' : '' }}>Proses</option>
                        <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="batal" {{ $status == 'batal' ? 'selected' : '' }}>Batal</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="{{ route('manager.laporan.export', request()->query()) }}" class="btn btn-success w-100">Export</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Kartu ringkasan statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card p-3 text-center"><h6>Total Pesanan</h6><h3>{{ $total }}</h3></div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center"><h6>Pending</h6><h3>{{ $byStatus['pending']->total ?? 0 }}</h3></div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center"><h6>Proses</h6><h3>{{ $byStatus['proses']->total ?? 0 }}</h3></div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center"><h6>Selesai</h6><h3>{{ $byStatus['selesai']->total ?? 0 }}</h3></div>
        </div>
    </div>

    {{-- Tabel Detail Pesanan --}}
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">Detail Pesanan</h5></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ruang Rawat</th>
                            <th>Paket Makanan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Alasan Batal</th> {{-- TAMBAHKAN HEADER INI --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pesanan as $p)
                        <tr>
                            <td><strong>{{ $p->ruangRawat->nama_ruang ?? 'N/A' }}</strong></td>
                            <td>{{ $p->paketMakanan->nama_paket ?? 'N/A' }}</td>
                            <td>{{ $p->tanggal->format('d M Y, H:i') }}</td>
                            <td>
                                @switch($p->status)
                                    @case('pending') <span class="badge bg-warning text-dark">Pending</span> @break
                                    @case('proses') <span class="badge bg-info">Proses</span> @break
                                    @case('selesai') <span class="badge bg-success">Selesai</span> @break
                                    @case('batal') <span class="badge bg-danger">Batal</span> @break
                                    @default <span class="badge bg-secondary">Unknown</span>
                                @endswitch
                            </td>
                            {{-- TAMBAHKAN KOLOM INI --}}
                            <td>{{ $p->alasan_batal ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            {{-- ubah colspan menjadi 5 --}}
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">Tidak ada data untuk rentang yang dipilih.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tabel Top Paket Makanan --}}
    <div class="card">
        <div class="card-header"><h5 class="mb-0">Top Paket Makanan</h5></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Paket</th>
                            <th class="text-end">Total Pesanan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($totalsByPaket as $paket)
                        <tr>
                            <td>{{ $paketMakanan[$paket->paket_makanan_id] ?? 'N/A' }}</td>
                            <td class="text-end">{{ $paket->total_pesanan }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center py-3">
                                <div class="text-muted">Tidak ada data paket makanan.</div>
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