<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Lapor Unair</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Kode CSS dari dashboard kita pindahkan ke sini --}}
    <style>
        html {
            scroll-behavior: smooth;
        }
        body {
            background-color: #f0f2f5;
        }
        .hero-section {
            background-image: url('{{ asset('images/gedung-rektorat.jpg') }}');
            background-size: cover;
            background-position: center;
            padding: 5rem 1.5rem;
            border-radius: 0.5rem;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
        }
        .stats-card {
            background-image: url('{{ asset('images/Union.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
            padding: 4rem 2rem;
            text-align: center;
        }
        .stats-card h1 { font-size: 3rem; font-weight: bold; }
        .image-card { border-radius: 25px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .login-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            max-width: 450px;
            margin: auto;
        }
    </style>
</head>
<body>

    {{-- 1. HEADER SEDERHANA UNTUK TAMU --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-light py-3">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('images/logo laporunair.png') }}" alt="Lapor Unair Logo" height="40">
            </a>
            <ul class="navbar-nav flex-row">
                <li class="nav-item me-3"><a class="nav-link" href="#">Beranda</a></li>
                <li class="nav-item me-3"><a class="nav-link" href="#">Tentang Layanan</a></li>
                <li class="nav-item">
                    <a href="{{ route('login') }}" class="btn btn-primary rounded-pill">Login</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container my-5">
        {{-- Ini adalah konten dari dashboard yang kita copy --}}
        {{-- Bagian Hero --}}
        <div class="hero-section text-center mb-5">
            <h1 class="display-4 fw-bold">LAPOR UNAIR</h1>
            <h2>Lapor Segera, Nyaman Bersama</h2>
        </div>

        {{-- Bagian Deskripsi & Tombol Aksi --}}
        <div class="row align-items-center mb-5">
            <div class="col-md-4 text-center">
                <img src="{{ asset('images/image.png') }}" class="img-fluid image-card" alt="Gerbang UNAIR">
            </div>
            <div class="col-md-8">
                <p class="lead">LaporUnair adalah layanan digital Universitas Airlangga untuk mempermudah civitas akademika dalam melaporkan berbagai kendala di kampus...</p>
                {{-- 2. TOMBOL INI AKAN MENGARAHKAN KE BAWAH --}}
                <a href="#login-section" class="btn btn-primary btn-lg scroll-to-login">Buat Laporan</a>
            </div>
        </div>

        {{-- Bagian Alur Pelaporan --}}
        <div class="row text-center mb-5 align-items-center">
            {{-- Konten alur pelaporan (4 ikon) diletakkan di sini --}}
        </div>

        {{-- Bagian Statistik Laporan (Gunakan Angka Statis untuk Tamu) --}}
        <div class="stats-card">
            <h3 class="mb-4">Jumlah Laporan Saat Ini</h3>
            <div class="row">
                <div class="col-4"><h1>3</h1><p>Terkirim</p></div>
                <div class="col-4"><h1>10</h1><p>Dalam Proses</p></div>
                <div class="col-4"><h1>25</h1><p>Selesai</p></div>
            </div>
        </div>
    </div>
    
    {{-- 3. BAGIAN LOGIN DI PALING BAWAH --}}
    <div id="login-section" class="container my-5 py-5">
        <div class="login-card">
            <h2 class="text-center mb-4">Login</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" class="form-control" type="password" name="password" required>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('password.request') }}" class="text-sm">Lupa password?</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Login</button>
                </div>
                <p class="text-center mt-4 text-sm">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
                </p>
            </form>
        </div>
    </div>

    {{-- Script untuk Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- 4. JAVASCRIPT UNTUK FUNGSI SMOOTH SCROLL --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scrollLinks = document.querySelectorAll('.scroll-to-login');
            
            scrollLinks.forEach(function(link) {
                link.addEventListener('click', function(event) {
                    event.preventDefault(); // Mencegah link berpindah halaman
                    
                    const targetId = this.getAttribute('href'); // Mendapatkan #login-section
                    const targetElement = document.querySelector(targetId);
                    
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>

