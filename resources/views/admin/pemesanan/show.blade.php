@extends('layouts.admin')

@section('title', 'Detail Pemesanan')

@section('styles')
<style>
    .stepper-wrapper {
        display: flex;
        justify-content: space-between;
        margin: 30px 0;
        position: relative;
    }
    .stepper-item {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
    }
    .stepper-item:not(:last-child):before {
        position: absolute;
        content: "";
        border-bottom: 3px solid #e0e0e0;
        width: 100%;
        top: 20px;
        left: 50%;
        z-index: 0;
    }
    .stepper-item.completed:not(:last-child):before {
        border-bottom: 3px solid #ff6b35;
    }
    .step-counter {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: #e0e0e0;
        margin-bottom: 10px;
        color: #666;
        font-weight: bold;
        font-size: 18px;
    }
    .stepper-item.completed .step-counter {
        background: linear-gradient(135deg, #ff6b35, #f4a261);
        color: white;
    }
    .stepper-item.active .step-counter {
        background: linear-gradient(135deg, #f4a261, #ffd6a5);
        color: white;
        box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.2);
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.2); }
        50% { box-shadow: 0 0 0 8px rgba(255, 107, 53, 0.1); }
    }
    .step-name {
        text-align: center;
        font-size: 13px;
        font-weight: 500;
        color: #666;
    }
    .stepper-item.completed .step-name {
        color: #ff6b35;
        font-weight: 600;
    }
    .stepper-item.active .step-name {
        color: #e85d04;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <h2><i class="bi bi-file-text"></i> Detail Pemesanan #{{ $pemesanan->id }}</h2>
        <a href="{{ route('admin.pemesanan.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

{{-- Progress Stepper --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="mb-3"><i class="bi bi-graph-up"></i> Progress Pemesanan</h5>
        <div class="stepper-wrapper">
            {{-- Step 1: Pesanan Dibuat --}}
            <div class="stepper-item completed">
                <div class="step-counter">
                    <i class="bi bi-check-lg"></i>
                </div>
                <div class="step-name">Pesanan<br>Dibuat</div>
            </div>

            {{-- Step 2: Bukti Diupload --}}
            <div class="stepper-item {{ $pemesanan->bukti_pembayaran ? 'completed' : '' }} {{ !$pemesanan->bukti_pembayaran ? 'active' : '' }}">
                <div class="step-counter">
                    @if($pemesanan->bukti_pembayaran)
                        <i class="bi bi-check-lg"></i>
                    @else
                        2
                    @endif
                </div>
                <div class="step-name">Bukti<br>Diupload</div>
            </div>

            {{-- Step 3: Pembayaran Lunas --}}
            <div class="stepper-item {{ $pemesanan->status_pembayaran == 'lunas' ? 'completed' : '' }} {{ $pemesanan->bukti_pembayaran && $pemesanan->status_pembayaran != 'lunas' ? 'active' : '' }}">
                <div class="step-counter">
                    @if($pemesanan->status_pembayaran == 'lunas')
                        <i class="bi bi-check-lg"></i>
                    @else
                        3
                    @endif
                </div>
                <div class="step-name">Pembayaran<br>Lunas</div>
            </div>

            {{-- Step 4: Barang Diambil --}}
            <div class="stepper-item {{ in_array($pemesanan->status_pengambilan, ['dalam_penggunaan', 'selesai']) ? 'completed' : '' }} {{ $pemesanan->status_pembayaran == 'lunas' && $pemesanan->status_pengambilan == 'belum_diambil' ? 'active' : '' }}">
                <div class="step-counter">
                    @if(in_array($pemesanan->status_pengambilan, ['dalam_penggunaan', 'selesai']))
                        <i class="bi bi-check-lg"></i>
                    @else
                        4
                    @endif
                </div>
                <div class="step-name">Barang<br>Diambil</div>
            </div>

            {{-- Step 5: Selesai --}}
            <div class="stepper-item {{ $pemesanan->status_pengambilan == 'selesai' ? 'completed' : '' }} {{ $pemesanan->status_pengambilan == 'dalam_penggunaan' ? 'active' : '' }}">
                <div class="step-counter">
                    @if($pemesanan->status_pengambilan == 'selesai')
                        <i class="bi bi-check-lg"></i>
                    @else
                        5
                    @endif
                </div>
                <div class="step-name">Selesai</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Informasi Pemesanan</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    @if($pemesanan->user_id)
                    {{-- Registered User --}}
                    <tr>
                        <th width="200">Tipe Pemesanan:</th>
                        <td><span class="badge bg-success">User Terdaftar</span></td>
                    </tr>
                    <tr>
                        <th width="200">Customer:</th>
                            <td>{{ $pemesanan->user?->name ?? $pemesanan->guest_nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $pemesanan->user?->email ?? $pemesanan->guest_email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Telepon:</th>
                        <td>{{ $pemesanan->user?->phone ?? $pemesanan->guest_phone ?? '-' }}</td>
                    </tr>
                    @else
                    {{-- Guest User --}}
                    <tr>
                        <th width="200">Tipe Pemesanan:</th>
                        <td><span class="badge bg-info">Guest / Tamu</span></td>
                    </tr>
                    <tr>
                        <th>Nama Pemesan:</th>
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
                        <th>Nama Acara:</th>
                        <td>{{ $pemesanan->nama_acara }}</td>
                    </tr>
                    <tr>
                        <th>Paket:</th>
                        <td>{{ $pemesanan->paket->nama_paket ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal:</th>
                        <td>{{ $pemesanan->tanggal_mulai->format('d M Y') }} - {{ $pemesanan->tanggal_selesai->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Alamat Acara:</th>
                        <td>{{ $pemesanan->alamat_acara }}</td>
                    </tr>
                    <tr>
                        <th>Total Harga:</th>
                        <td class="fs-5 fw-bold">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Status Pembayaran:</th>
                        <td>
                            <span class="badge bg-{{ $pemesanan->status_pembayaran == 'lunas' ? 'success' : 'warning' }} fs-6">
                                {{ ucfirst($pemesanan->status_pembayaran) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Status Pengambilan:</th>
                        <td>
                            <span class="badge bg-{{ $pemesanan->status_pengambilan == 'selesai' ? 'success' : ($pemesanan->status_pengambilan == 'dalam_penggunaan' ? 'info' : 'secondary') }} fs-6">
                                {{ ucfirst(str_replace('_', ' ', $pemesanan->status_pengambilan)) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Update Status -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Update Status Pengambilan</h5>
            </div>
            <div class="card-body">
                @if($pemesanan->status_pembayaran != 'lunas')
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> <strong>Perhatian!</strong> Pembayaran belum lunas. Pastikan pembayaran sudah divalidasi sebelum mengubah status pengambilan.
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.pemesanan.status', $pemesanan->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status Pengambilan</label>
                        <select name="status_pengambilan" class="form-select">
                            <option value="belum_diambil" {{ $pemesanan->status_pengambilan == 'belum_diambil' ? 'selected' : '' }}>
                                Belum Diambil
                            </option>
                            <option value="dalam_penggunaan" {{ $pemesanan->status_pengambilan == 'dalam_penggunaan' ? 'selected' : '' }}>
                                Dalam Penggunaan
                            </option>
                            <option value="selesai" {{ $pemesanan->status_pengambilan == 'selesai' ? 'selected' : '' }}>
                                Selesai (Barang Dikembalikan)
                            </option>
                        </select>
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Update status ini sesuai kondisi aktual pengambilan barang.
                        </small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle"></i> Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-receipt"></i> Bukti Pembayaran</h5>
            </div>
            <div class="card-body">
                @if($pemesanan->bukti_pembayaran)
                    <div class="mb-3">
                        <img src="{{ asset($pemesanan->bukti_pembayaran) }}" class="img-fluid rounded shadow-sm" alt="Bukti Pembayaran">
                    </div>
                    
                    @if($pemesanan->status_pembayaran == 'pending')
                        <div class="alert alert-warning mb-3">
                            <i class="bi bi-clock-history"></i> <strong>Menunggu Validasi</strong>
                            <p class="mb-0 mt-2 small">Bukti pembayaran memerlukan validasi. Pastikan nominal dan detail transfer sudah sesuai.</p>
                        </div>
                        <form method="POST" action="{{ route('admin.pemesanan.validasi', $pemesanan->id) }}" onsubmit="return confirm('Validasi pembayaran sebesar Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}?')">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle"></i> Validasi Pembayaran
                            </button>
                        </form>
                    @else
                        <div class="alert alert-success mb-0">
                            <i class="bi bi-check-circle-fill"></i> <strong>Pembayaran Tervalidasi</strong>
                            <p class="mb-0 mt-2 small">Pembayaran telah diverifikasi dan dinyatakan lunas.</p>
                        </div>
                    @endif
                @else
                    <div class="alert alert-warning mb-3">
                        <i class="bi bi-exclamation-triangle"></i> <strong>Belum Ada Bukti</strong>
                        <p class="mb-0 mt-2 small">Customer belum mengirim bukti atau admin belum mengunggahnya ke sistem.</p>
                    </div>

                    <form method="POST" action="{{ route('admin.pemesanan.upload', $pemesanan->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Upload Bukti Pembayaran</label>
                            <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*" required>
                            <small class="text-muted">Format: JPG, PNG. Max: 2MB</small>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-upload"></i> Upload Bukti oleh Admin
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Timeline Status --}}
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Status</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    {{-- Pesanan Dibuat --}}
                    <div class="timeline-item mb-3">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-check2 text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Pesanan Dibuat</h6>
                                <p class="text-muted small mb-1">
                                     <i class="bi bi-person"></i> {{ $pemesanan->user?->name ?? $pemesanan->guest_nama ?? '-' }}
                                </p>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-calendar3"></i> {{ $pemesanan->created_at->format('d M Y, H:i') }}
                                    <span class="text-muted">({{ $pemesanan->created_at->diffForHumans() }})</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($pemesanan->bukti_pembayaran)
                    {{-- Bukti Diupload --}}
                    <div class="timeline-item mb-3">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-check2 text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Bukti Pembayaran Diupload</h6>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-calendar3"></i> {{ $pemesanan->updated_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($pemesanan->status_pembayaran == 'lunas')
                    {{-- Pembayaran Lunas --}}
                    <div class="timeline-item mb-3">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-check2 text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Pembayaran Tervalidasi</h6>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-person-check"></i> Divalidasi oleh Admin
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($pemesanan->status_pengambilan == 'dalam_penggunaan')
                    {{-- Barang Diambil --}}
                    <div class="timeline-item mb-3">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #f4a261, #ffd6a5);">
                                    <i class="bi bi-box-arrow-right text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Barang Sedang Digunakan</h6>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-hourglass-split"></i> Dalam penggunaan
                                </p>
                            </div>
                        </div>
                    </div>
                    @elseif($pemesanan->status_pengambilan == 'selesai')
                    {{-- Selesai --}}
                    <div class="timeline-item mb-3">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-check-circle text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Pemesanan Selesai</h6>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-check-all"></i> Barang telah dikembalikan
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(!$pemesanan->bukti_pembayaran || $pemesanan->status_pembayaran != 'lunas' || $pemesanan->status_pengambilan == 'belum_diambil')
                    {{-- Status Saat Ini --}}
                    <div class="timeline-item">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-clock text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Status Saat Ini</h6>
                                <p class="text-muted small mb-0">
                                    @if(!$pemesanan->bukti_pembayaran)
                                        <i class="bi bi-arrow-right"></i> Menunggu customer upload bukti pembayaran
                                    @elseif($pemesanan->status_pembayaran != 'lunas')
                                        <i class="bi bi-arrow-right"></i> Menunggu validasi pembayaran
                                    @elseif($pemesanan->status_pengambilan == 'belum_diambil')
                                        <i class="bi bi-arrow-right"></i> Menunggu pengambilan barang oleh customer
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
