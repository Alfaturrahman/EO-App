@extends('layouts.admin')

@section('title', 'Detail Paket')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-box-seam"></i> Detail Paket</h2>
    <div>
        <a href="{{ route('admin.paket.edit', $paket->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <form action="{{ route('admin.paket.destroy', $paket->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus paket ini? Data tidak bisa dikembalikan!')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash"></i> Hapus
            </button>
        </form>
        <a href="{{ route('admin.paket.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                @if($paket->foto)
                    <img src="{{ asset($paket->foto) }}" class="img-fluid rounded" alt="{{ $paket->nama_paket }}">
                @else
                    <img src="https://via.placeholder.com/400x300?text=No+Image" class="img-fluid rounded" alt="No Image">
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header fw-bold">
                <i class="bi bi-info-circle"></i> Informasi Paket
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted">Nama Paket</label>
                    <h5>{{ $paket->nama_paket }}</h5>
                </div>
                <div class="mb-3">
                    <label class="text-muted">Deskripsi</label>
                    <p>{{ $paket->deskripsi ?? '-' }}</p>
                </div>
                <div class="mb-3">
                    <label class="text-muted">Harga Total</label>
                    <h4 class="text-success">Rp {{ number_format($paket->harga_total, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header fw-bold">
                <i class="bi bi-box"></i> Barang-barang dalam Paket
            </div>
            <div class="card-body">
                @if($paket->barangs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Barang</th>
                                    <th width="15%">Harga Sewa</th>
                                    <th width="10%">Jumlah</th>
                                    <th width="15%">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalItems = 0; @endphp
                                @foreach($paket->barangs as $index => $barang)
                                    @php
                                        $jumlah = $barang->pivot->jumlah;
                                        $subtotal = $barang->harga_sewa * $jumlah;
                                        $totalItems += $jumlah;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $barang->nama_barang }}</td>
                                        <td>Rp {{ number_format($barang->harga_sewa, 0, ',', '.') }}</td>
                                        <td>{{ $jumlah }} unit</td>
                                        <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">Total</th>
                                    <th>{{ $totalItems }} unit</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle"></i> Belum ada barang dalam paket ini
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
