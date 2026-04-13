@extends('layouts.app')

@section('title', $paket->nama_paket)

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active">{{ $paket->nama_paket }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-6 mb-4">
            @if($paket->foto)
                <img src="{{ asset($paket->foto) }}" class="img-fluid rounded shadow" alt="{{ $paket->nama_paket }}">
            @else
                <div class="bg-secondary rounded shadow d-flex align-items-center justify-content-center" style="height: 400px;">
                    <i class="bi bi-image text-white" style="font-size: 6rem;"></i>
                </div>
            @endif
        </div>
        <div class="col-lg-6">
            <h1 class="mb-3">{{ $paket->nama_paket }}</h1>
            <h3 class="text-primary mb-4">Rp {{ number_format($paket->harga_total, 0, ',', '.') }}</h3>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="bi bi-info-circle"></i> Deskripsi Paket</h5>
                    <p class="card-text" style="white-space: pre-line;">{{ $paket->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                </div>
            </div>

            @if($paket->barangs->count() > 0)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="bi bi-box"></i> Paket Ini Termasuk:</h5>
                    <ul class="list-group list-group-flush">
                        @foreach($paket->barangs as $barang)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>
                                    <i class="bi bi-check-circle-fill text-success"></i> 
                                    {{ $barang->nama_barang }}
                                </span>
                                <span class="badge bg-primary rounded-pill">{{ $barang->pivot->jumlah }} unit</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <a href="{{ route('pemesanan.create', $paket->id) }}" class="btn btn-primary btn-lg w-100">
                <i class="bi bi-cart-plus"></i> Pesan Sekarang
            </a>
        </div>
    </div>
</div>
@endsection
