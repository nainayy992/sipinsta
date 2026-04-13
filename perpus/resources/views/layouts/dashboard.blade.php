<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} | SIPINSTA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --maroon: #662222;
            --maroon-light: #800000;
            --bg-body: #f8f9fa;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
        }
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background-color: var(--maroon);
            color: white;
            transition: all 0.3s;
            z-index: 1000;
        }
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 12px 20px;
            border-radius: 10px;
            margin: 5px 15px;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }
        .nav-link:hover, .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }
        .nav-link i {
            margin-right: 12px;
            font-size: 1.2rem;
        }
        .main-content {
            margin-left: 260px;
            padding: 40px;
            transition: all 0.3s;
        }
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .btn-maroon {
            background-color: var(--maroon);
            color: white;
            border-radius: 10px;
            padding: 10px 20px;
            border: none;
        }
        .btn-maroon:hover {
            background-color: var(--maroon-light);
            color: white;
        }
        .btn-outline-maroon {
            color: var(--maroon);
            border: 1px solid var(--maroon);
            border-radius: 8px;
            padding: 5px 15px;
            background: transparent;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-outline-maroon:hover {
            background-color: var(--maroon);
            color: white;
        }
        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: -260px;
            }
            .sidebar.active {
                margin-left: 0;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-bold">SIPINSTA</h5>
            <button class="btn btn-sm text-white d-lg-none" id="sidebarClose">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="mt-4">
            <a href="{{ route(Auth::user()->role . '.dashboard') }}" class="nav-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            @if(Auth::user()->role === 'admin')
                <div class="px-4 mt-4 mb-2 small text-uppercase text-white-50 fw-bold">Manajemen</div>
                <a href="{{ route('admin.buku.index') }}" class="nav-link {{ request()->routeIs('*.buku.*') ? 'active' : '' }}">
                    <i class="bi bi-book"></i> Kelola Buku
                </a>
                <a href="{{ route('admin.siswa.index') }}" class="nav-link {{ request()->routeIs('*.siswa.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Kelola Anggota
                </a>
                <a href="{{ route('admin.transaksi.index') }}" class="nav-link {{ request()->routeIs('*.transaksi.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right"></i> Transaksi
                </a>
            @else
                <div class="px-4 mt-4 mb-2 small text-uppercase text-white-50 fw-bold">Layanan</div>
                <a href="{{ route('siswa.buku.cari') }}" class="nav-link {{ request()->routeIs('*.buku.*') ? 'active' : '' }}">
                    <i class="bi bi-search"></i> Cari Buku
                </a>
                <a href="{{ route('siswa.peminjaman') }}" class="nav-link {{ request()->routeIs('*.peminjaman') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i> Peminjaman Saya
                </a>
            @endif

            <div class="px-4 mt-4 mb-2 small text-uppercase text-white-50 fw-bold">Akun</div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link w-100 bg-transparent border-0 text-start">
                    <i class="bi bi-box-arrow-left"></i> Keluar
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <nav class="navbar d-lg-none mb-4 p-0">
            <button class="btn btn-maroon" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
        </nav>

        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-dark">@yield('title', 'Welcome')</h3>
                <div class="text-end">
                    <span class="text-muted d-block small">Selamat datang,</span>
                    <span class="fw-bold text-maroon">{{ Auth::user()->name }}</span>
                </div>
            </div>

            @yield('dashboard_content')
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', () => {
            document.getElementById('sidebar').classList.add('active');
        });
        document.getElementById('sidebarClose')?.addEventListener('click', () => {
            document.getElementById('sidebar').classList.remove('active');
        });
    </script>
    @stack('scripts')
</body>
</html>
