@extends('layouts.app')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Laporan</h3>
        <a href="{{ route('manager.dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Tabel Pesanan Pending --}}
    <div class="card mb-4">
        <div class="card-header"><h4 class="mb-0">Pesanan Menunggu Proses (Pending)</h4></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ruang Rawat</th>
                            <th>Paket Makanan</th>
                            <th>Tanggal Pesan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingPesanan as $pesanan)
                        <tr>
                            <td><strong>{{ $pesanan->ruangRawat->nama_ruang ?? 'N/A' }}</strong></td>
                            <td>{{ $pesanan->paketMakanan->nama_paket ?? 'N/A' }}</td>
                            <td>{{ $pesanan->tanggal->format('d M Y, H:i') }}</td>
                            <td class="text-end">
                                <form action="{{ route('manager.pesanan.process', $pesanan->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">Proses</button>
                                </form>
                                {{-- Tombol ini HANYA untuk membuka pop-up --}}
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal" data-pesanan-id="{{ $pesanan->id }}">
                                    Batal
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4">Tidak ada pesanan yang menunggu proses.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tabel Pesanan Diproses --}}
    <div class="card">
        <div class="card-header"><h4 class="mb-0">Pesanan Sedang Diproses</h4></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ruang Rawat</th>
                            <th>Paket Makanan</th>
                            <th>Tanggal Pesan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prosesPesanan as $pesanan)
                        <tr>
                            <td><strong>{{ $pesanan->ruangRawat->nama_ruang ?? 'N/A' }}</strong></td>
                            <td>{{ $pesanan->paketMakanan->nama_paket ?? 'N/A' }}</td>
                            <td>{{ $pesanan->tanggal->format('d M Y, H:i') }}</td>
                            <td class="text-end">
                                <form action="{{ route('manager.pesanan.complete', $pesanan->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Selesai</button>
                                </form>
                                {{-- Tombol ini juga HANYA untuk membuka pop-up --}}
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal" data-pesanan-id="{{ $pesanan->id }}">
                                    Batal
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4">Tidak ada pesanan yang sedang diproses.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal untuk Alasan Pembatalan --}}
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Batalkan Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- Form ini yang akan mengirim data pembatalan --}}
            <form id="cancelForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="alasan_batal" class="form-label">Mohon masukkan alasan pembatalan:</label>
                        <textarea class="form-control" id="alasan_batal" name="alasan_batal" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger">Ya, Batalkan Pesanan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- JavaScript untuk mengatur action form di modal --}}
<script>
    var cancelModal = document.getElementById('cancelModal');
    cancelModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var pesananId = button.getAttribute('data-pesanan-id');
        var actionUrl = '{{ url("manager/pesanan") }}/' + pesananId + '/batal';
        var cancelForm = cancelModal.querySelector('#cancelForm');
        cancelForm.setAttribute('action', actionUrl);
    });
</script>
@endpush