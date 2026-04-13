<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Sonsun')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-orange: #ff6b35;
            --secondary-orange: #f4a261;
            --dark-orange: #e85d04;
            --accent-orange: #d62828;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary-orange) 0%, var(--dark-orange) 100%);
            box-shadow: 2px 0 10px rgba(255, 107, 53, 0.2);
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            transition: all 0.3s ease;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: rgba(255,255,255,0.15);
            border-left: 4px solid #fff;
            padding-left: 24px;
        }
        .main-content {
            background-color: #fff5f0;
            min-height: 100vh;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--dark-orange), var(--primary-orange));
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(255, 107, 53, 0.3);
        }
        .btn-warning {
            background-color: var(--secondary-orange);
            border-color: var(--secondary-orange);
            color: white;
        }
        .btn-warning:hover {
            background-color: #e59151;
            border-color: #e59151;
            color: white;
        }
        .btn-danger {
            background-color: var(--accent-orange);
            border-color: var(--accent-orange);
        }
        .btn-danger:hover {
            background-color: #bb2525;
            border-color: #bb2525;
        }
        .text-primary {
            color: var(--primary-orange) !important;
        }
        .bg-primary {
            background-color: var(--primary-orange) !important;
        }
        .badge.bg-primary {
            background-color: var(--primary-orange) !important;
        }
        .badge.bg-warning {
            background-color: var(--secondary-orange) !important;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="p-3">
                    <h4 class="text-white">
                        <i class="bi bi-shield"></i> Admin Panel
                    </h4>
                    <hr class="text-white">
                </div>
                <nav>
                    <a href="{{ route('admin.dashboard') }}" class="@if(Request::is('admin/dashboard')) active @endif">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.paket.index') }}" class="@if(Request::is('admin/paket*')) active @endif">
                        <i class="bi bi-box-seam"></i> Kelola Paket
                    </a>
                    <a href="{{ route('admin.barang.index') }}" class="@if(Request::is('admin/barang*')) active @endif">
                        <i class="bi bi-basket"></i> Kelola Barang
                    </a>
                    <a href="{{ route('admin.pemesanan.index') }}" class="@if(Request::is('admin/pemesanan*')) active @endif">
                        <i class="bi bi-clipboard-check"></i> Pemesanan
                    </a>
                    <hr class="text-white">
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm w-100 mx-3" style="width: calc(100% - 40px) !important;">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
