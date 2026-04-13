@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-speedometer2"></i> Dashboard</h2>
    <span class="text-muted">Selamat datang, {{ session('admin_name') }}</span>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase mb-0">Total Pesanan</h6>
                        <h2 class="mb-0">{{ $totalPesanan }}</h2>
                    </div>
                    <i class="bi bi-clipboard-check" style="font-size: 3rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase mb-0">In Progress</h6>
                        <h2 class="mb-0">{{ $pesananInProgress }}</h2>
                    </div>
                    <i class="bi bi-hourglass-split" style="font-size: 3rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase mb-0">Total Pemasukan</h6>
                        <h2 class="mb-0">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h2>
                    </div>
                    <i class="bi bi-cash" style="font-size: 3rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik -->
<div class="row g-4 mb-5">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Pemesanan Bulanan (12 Bulan)</h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyOrdersChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-currency-dollar"></i> Pemasukan Bulanan</h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyRevenueChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> Trend Tahunan Pemesanan</h5>
            </div>
            <div class="card-body">
                <canvas id="yearlyOrdersChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-cash-stack"></i> Trend Tahunan Pemasukan</h5>
            </div>
            <div class="card-body">
                <canvas id="yearlyRevenueChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Pemesanan Terbaru</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Paket</th>
                        <th>Total</th>
                        <th>Status Pembayaran</th>
                        <th>Status Pengambilan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPemesanans as $p)
                        <tr>
                            <td>#{{ $p->id }}</td>
                            <td>{{ $p->user->name }}</td>
                            <td>{{ $p->paket->nama_paket ?? '-' }}</td>
                            <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $p->status_pembayaran == 'lunas' ? 'success' : 'warning' }}">
                                    {{ ucfirst($p->status_pembayaran) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $p->status_pengambilan)) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.pemesanan.show', $p->id) }}" class="btn btn-sm btn-primary">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada pemesanan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Generate 12 bulan labels
    const monthLabels = [];
    for (let i = 11; i >= 0; i--) {
        const d = new Date();
        d.setMonth(d.getMonth() - i);
        monthLabels.push(d.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' }));
    }

    // Generate 5 tahun labels
    const yearLabels = [];
    for (let i = 4; i >= 0; i--) {
        yearLabels.push((new Date().getFullYear() - i).toString());
    }

    // Chart Pemesanan Bulanan
    new Chart(document.getElementById('monthlyOrdersChart'), {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Jumlah Pemesanan',
                data: @json($monthlyData),
                borderColor: 'rgb(255, 107, 53)',
                backgroundColor: 'rgba(255, 107, 53, 0.2)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
                title: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // Chart Pemasukan Bulanan
    new Chart(document.getElementById('monthlyRevenueChart'), {
        type: 'bar',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Pemasukan (Rp)',
                data: @json($monthlyRevenue),
                backgroundColor: 'rgba(244, 162, 97, 0.7)',
                borderColor: 'rgb(244, 162, 97)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value/1000000).toFixed(0) + 'jt';
                        }
                    }
                }
            }
        }
    });

    // Chart Tahunan Pemesanan
    new Chart(document.getElementById('yearlyOrdersChart'), {
        type: 'line',
        data: {
            labels: yearLabels,
            datasets: [{
                label: 'Total Pemesanan',
                data: @json($yearlyData),
                borderColor: 'rgb(232, 93, 4)',
                backgroundColor: 'rgba(232, 93, 4, 0.2)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // Chart Tahunan Pemasukan
    new Chart(document.getElementById('yearlyRevenueChart'), {
        type: 'bar',
        data: {
            labels: yearLabels,
            datasets: [{
                label: 'Total Pemasukan (Rp)',
                data: @json($yearlyRevenue),
                backgroundColor: 'rgba(255, 160, 122, 0.7)',
                borderColor: 'rgb(255, 160, 122)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value/1000000).toFixed(0) + 'jt';
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
