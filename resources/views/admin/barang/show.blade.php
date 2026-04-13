@extends('layouts.admin')

@section('title', 'Detail Barang')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <h2><i class="bi bi-basket"></i> Detail Barang</h2>
    <div>
        <a href="{{ route('admin.barang.edit', $barang->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <form action="{{ route('admin.barang.destroy', $barang->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus barang ini? Data tidak bisa dikembalikan!')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash"></i> Hapus
            </button>
        </form>
        <a href="{{ route('admin.barang.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                @if($barang->foto)
                    <img src="{{ asset($barang->foto) }}" class="img-fluid rounded" alt="{{ $barang->nama_barang }}">
                @else
                    <img src="https://via.placeholder.com/400x300?text=No+Image" class="img-fluid rounded" alt="No Image">
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header fw-bold">
                <i class="bi bi-info-circle"></i> Informasi Barang
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="text-muted">ID Barang</label>
                    </div>
                    <div class="col-md-9">
                        <strong>#{{ $barang->id }}</strong>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="text-muted">Nama Barang</label>
                    </div>
                    <div class="col-md-9">
                        <h5>{{ $barang->nama_barang }}</h5>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="text-muted">Deskripsi</label>
                    </div>
                    <div class="col-md-9">
                        <p style="white-space: pre-line;">{{ $barang->deskripsi ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="text-muted">Harga Sewa</label>
                    </div>
                    <div class="col-md-9">
                        <h4 class="text-primary">Rp {{ number_format($barang->harga_sewa, 0, ',', '.') }}</h4>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="text-muted">Stok Tersedia</label>
                    </div>
                    <div class="col-md-9">
                        <h5>
                            <span class="badge {{ $barang->stok > 0 ? 'bg-success' : 'bg-danger' }} fs-6">
                                {{ $barang->stok }} Unit
                            </span>
                        </h5>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="text-muted">Terakhir Update</label>
                    </div>
                    <div class="col-md-9">
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> {{ $barang->updated_at->format('d M Y, H:i') }} 
                            ({{ $barang->updated_at->diffForHumans() }})
                        </small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <label class="text-muted">Dibuat Pada</label>
                    </div>
                    <div class="col-md-9">
                        <small class="text-muted">
                            <i class="bi bi-calendar"></i> {{ $barang->created_at->format('d M Y, H:i') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        @if($barang->pakets->count() > 0)
        <div class="card mt-3">
            <div class="card-header fw-bold">
                <i class="bi bi-box-seam"></i> Digunakan dalam Paket
            </div>
            <div class="card-body">
                <div class="list-group">
                    @foreach($barang->pakets as $paket)
                        <a href="{{ route('admin.paket.show', $paket->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="bi bi-box-seam"></i> {{ $paket->nama_paket }}
                            </span>
                            <span class="badge bg-primary rounded-pill">{{ $paket->pivot->jumlah }} unit</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
