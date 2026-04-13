@extends('layouts.admin')

@section('title', 'Kelola Pemesanan')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-clipboard-check"></i> Kelola Pemesanan</h2>
</div>

<!-- Search & Filter -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.pemesanan.index') }}">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama acara/customer..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status_pembayaran" class="form-select form-select-sm">
                        <option value="">Status Bayar</option>
                        <option value="pending" {{ request('status_pembayaran') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="lunas" {{ request('status_pembayaran') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status_pengambilan" class="form-select form-select-sm">
                        <option value="">Status Pengambilan</option>
                        <option value="belum_diambil" {{ request('status_pengambilan') == 'belum_diambil' ? 'selected' : '' }}>Belum Diambil</option>
                        <option value="dalam_penggunaan" {{ request('status_pengambilan') == 'dalam_penggunaan' ? 'selected' : '' }}>Dalam Penggunaan</option>
                        <option value="selesai" {{ request('status_pengambilan') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="tanggal_dari" class="form-control form-control-sm" placeholder="Dari" value="{{ request('tanggal_dari') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="tanggal_sampai" class="form-control form-control-sm" placeholder="Sampai" value="{{ request('tanggal_sampai') }}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Paket</th>
                        <th>Tanggal Acara</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pemesanans as $p)
                        <tr>
                            <td>#{{ $p->id }}</td>
                            <td>{{ $p->user->name }}</td>
                            <td>{{ $p->paket->nama_paket ?? '-' }}</td>
                            <td>{{ $p->tanggal_mulai->format('d/m/Y') }}</td>
                            <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $p->status_pembayaran == 'lunas' ? 'success' : 'warning' }}">
                                    {{ ucfirst($p->status_pembayaran) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    {{ ucfirst(str_replace('_', ' ', $p->status_pengambilan)) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.pemesanan.show', $p->id) }}" class="btn btn-sm btn-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada pemesanan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
