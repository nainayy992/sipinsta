<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SIPINSTA | Perpustakaan Sekolah</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/LandingPage.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg navbar-dark position-absolute w-100 custom-navbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('images/LOGO.png') }}" class="logo-navbar me-2" alt="Logo">
        </a>

        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link text-uppercase fw-bold" href="#home">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-uppercase fw-bold" href="#about">ABOUT</a>
                </li>
                <li class="nav-item mx-2 text-white d-none d-lg-block">|</li>
                <li class="nav-item ms-2">
                    <a class="btn btn-custom-login px-4 d-flex align-items-center" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Masuk
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- ================= HERO ================= -->
<section id="home" class="hero-section">
    <div class="overlay"></div>
    <div class="container hero-content text-white text-center">
        <p class="welcome-text mb-2">Selamat datang di website</p>
        <h1 class="hero-title">SIPINSTA</h1>
        <p class="hero-desc mt-3 mx-auto col-lg-7">
            Website <span class="fw-bold">SIPINSTA</span> adalah sistem peminjaman buku sekolah berbasis web
            yang memudahkan admin dan siswa dalam pengelolaan perpustakaan
            serta peminjaman dan pengembalian buku secara digital.
        </p>
    </div>
</section>

<!-- ================= ABOUT ================= -->
<section id="about" class="about-section py-5">
    <div class="container text-center">

        <h3 class="fw-bold mb-4">Tentang Kami</h3>

        <p class="mb-5 mx-auto" style="max-width: 750px; color: #555;">
            Website peminjaman buku ini dirancang untuk memudahkan siswa melakukan
            peminjaman dan pengembalian buku secara online serta mendukung
            peningkatan literasi sekolah.
        </p>

        <!-- ROW WAJIB -->
        <div class="row g-4 justify-content-center">

            <!-- CARD 1 -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="feature-card h-100">
                    <div class="icon icon-bg-red">
                        <i class="bi bi-lightning-fill"></i>
                    </div>
                    <h5 class="fw-bold mt-4">Akses Mudah & Cepat</h5>
                    <p class="text-muted">
                        Peminjaman buku online tanpa proses rumit.
                    </p>
                </div>
            </div>

            <!-- CARD 2 -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="feature-card h-100">
                    <div class="icon icon-bg-blue">
                        <i class="bi bi-list-task"></i>
                    </div>
                    <h5 class="fw-bold mt-4">Pengelolaan Terintegrasi</h5>
                    <p class="text-muted">
                        Data buku dan peminjaman tercatat rapi dan aman.
                    </p>
                </div>
            </div>

            <!-- CARD 3 -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="feature-card h-100">
                    <div class="icon icon-bg-green">
                        <i class="bi bi-book"></i>
                    </div>
                    <h5 class="fw-bold mt-4">Mendukung Literasi</h5>
                    <p class="text-muted">
                        Meningkatkan minat baca siswa.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ================= FOOTER ================= -->
<footer class="footer">
    <div class="container text-white pt-5 pb-4">
        <div class="row">

            <div class="col-md-3 mb-4">
                <h6 class="fw-bold mb-3">Layanan</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">Peminjaman Buku</li>
                    <li class="mb-2">Pengembalian Buku</li>
                    <li class="mb-2">Reservasi Buku</li>
                </ul>
            </div>

            <div class="col-md-3 mb-4">
                <h6 class="fw-bold mb-3">Dukungan</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">Status Peminjaman</li>
                    <li class="mb-2">Riwayat Peminjaman</li>
                    <li class="mb-2">Notifikasi Pengingat</li>
                    <li class="mb-2">Bantuan Pengguna</li>
                </ul>
            </div>

            <div class="col-md-5 mb-4 ms-auto">
                <h6 class="fw-bold mb-3">Kontak</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="bi bi-telephone me-2"></i> (021) 5523429</li>
                    <li class="mb-2"><i class="bi bi-instagram me-2"></i> smkn4kotatangerang</li>
                    <li class="mb-2"><i class="bi bi-geo-alt me-2"></i> Jl. Veteran No. 1A Babakan, Tangerang - Banten</li>
                </ul>
            </div>

        </div>

        <div class="border-top border-secondary pt-4 mt-4 text-center">
            <p class="mb-0 small opacity-75">
                © 2026 RPL SMKN 4 TANGERANG. Hak cipta dilindungi.
            </p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>