@extends('layouts.app')

@section('title', 'Pemesanan Berhasil')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    </div>
                    
                    <h2 class="mb-3">Pemesanan Berhasil!</h2>
                    <p class="text-muted mb-4">Terima kasih telah melakukan pemesanan di Sonsun Event Organizer</p>

                    <div class="alert alert-info mb-4">
                        <h5 class="mb-3"><i class="bi bi-bookmark-star"></i> Simpan ID Pemesanan Anda</h5>
                        <h3 class="fw-bold text-primary">#{{ $pemesanan->id }}</h3>
                        <p class="mb-0 mt-3 small">Gunakan ID ini untuk tracking status pesanan Anda</p>
                    </div>

                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="mb-3 fw-bold">Detail Pemesanan</h6>
                            <table class="table table-borderless mb-0 text-start">
                                <tr>
                                    <th width="150">Nama Acara:</th>
                                    <td>{{ $pemesanan->nama_acara }}</td>
                                </tr>
                                <tr>
                                    <th>Paket:</th>
                                    <td>{{ $pemesanan->paket->nama_paket }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal:</th>
                                    <td>{{ $pemesanan->tanggal_mulai->format('d M Y') }} - {{ $pemesanan->tanggal_selesai->format('d M Y') }}</td>
                                </tr>
                                @if($pemesanan->guest_nama)
                                <tr>
                                    <th>Pemesan:</th>
                                    <td>{{ $pemesanan->guest_nama }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $pemesanan->guest_email }}</td>
                                </tr>
                                <tr>
                                    <th>Telepon:</th>
                                    <td>{{ $pemesanan->guest_phone }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Total Harga:</th>
                                    <td class="fw-bold text-primary">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="alert alert-warning mb-4">
                        <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle"></i> Langkah Selanjutnya</h6>
                        <ol class="text-start mb-0 small">
                            <li>Transfer pembayaran ke rekening kami</li>
                            <li>Hubungi admin untuk upload bukti pembayaran dengan menyertakan ID Pemesanan</li>
                            <li>Tunggu konfirmasi dari admin</li>
                        </ol>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ url('/') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-house"></i> Kembali ke Beranda
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="https://wa.me/6281234567890?text=Halo,%20saya%20telah%20melakukan%20pemesanan%20dengan%20ID:%20{{ $pemesanan->id }}" 
                               target="_blank" class="btn btn-success w-100">
                                <i class="bi bi-whatsapp"></i> Hubungi Admin
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
