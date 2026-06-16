@extends('layouts.app')

@section('title', 'Status Pesanan Guest')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('guest.tracking.form') }}">Tracking Guest</a></li>
            <li class="breadcrumb-item active">Detail Pesanan</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-receipt"></i> Status Pesanan #{{ $pemesanan->id }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="220">Nama Pemesan:</th>
                            <td>{{ $pemesanan->guest_nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Email Pemesan:</th>
                            <td>{{ $pemesanan->guest_email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Nama Acara:</th>
                            <td>{{ $pemesanan->nama_acara }}</td>
                        </tr>
                        <tr>
                            <th>Paket:</th>
                            <td>{{ $pemesanan->paket->nama_paket ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Acara:</th>
                            <td>{{ $pemesanan->tanggal_mulai->format('d M Y') }} - {{ $pemesanan->tanggal_selesai->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Alamat Acara:</th>
                            <td>{{ $pemesanan->alamat_acara }}</td>
                        </tr>
                        <tr>
                            <th>Total Harga:</th>
                            <td class="fw-bold text-primary">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Status Pembayaran:</th>
                            <td>
                                <span class="badge bg-{{ $pemesanan->status_pembayaran == 'lunas' ? 'success' : 'warning' }}">
                                    {{ ucfirst($pemesanan->status_pembayaran) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Status Pengambilan:</th>
                            <td>
                                <span class="badge bg-info">
                                    {{ ucfirst(str_replace('_', ' ', $pemesanan->status_pengambilan)) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="bi bi-info-circle"></i> Informasi</h6>
                    <ul class="mb-0">
                        <li>Jika status pembayaran masih pending, silakan kirim bukti pembayaran ke admin.</li>
                        <li>Tracking guest membutuhkan verifikasi OTP setiap kali sesi browser berakhir.</li>
                        <li>Untuk proses lebih praktis, Anda bisa daftar akun dan login.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
