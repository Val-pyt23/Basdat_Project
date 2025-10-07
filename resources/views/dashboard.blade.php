@extends('layouts.app')

{{-- Kirim CSS kustom ke layout utama --}}
@push('styles')
<style>
    body {
        /* Warna dasar yang netral */
        background-color: #f8f9fa;
        /* Dua sumber cahaya radial yang menyebar dengan lembut */
        background-image: 
            radial-gradient(circle at top left, rgba(255, 221, 114, 0.2), transparent 100%),
            radial-gradient(circle at bottom right, rgba(144, 202, 249, 0.3), transparent 100%);
        background-attachment: fixed;
    }

    .hero-section {
        background-image: url('{{ asset('images/image_main.png') }}'); /* Ganti dengan gambar hero yang besar */
        background-size: cover;
        background-position: center;
        padding: 8rem 1.5rem; /* Padding lebih besar untuk memperbesar ke bawah */
        border-radius: 0.5rem;
        color: white;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
        margin-bottom: 3rem;
        position: relative; /* Untuk menempatkan logo LaporUnair di atas gambar */
    }
    .hero-title-container {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        width: 80%; /* Batasi lebar container judul */
    }
    .hero-title-container img {
        height: 60px; /* Ukuran logo LaporUnair di hero */
        margin-bottom: 10px;
    }
    .hero-title-container h2 {
        font-size: 1.5rem;
        font-weight: normal;
        margin-top: 0;
    }

    .description-section {
        margin-bottom: 5rem;
    }
    .description-section p.lead {
        line-height: 1.8; /* Rapatkan baris teks */
        margin-bottom: 2rem;
    }
    .image-card {
        border-radius: 25px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        width: 100%;
        height: auto;
        max-width: 350px; /* Batasi ukuran gambar gerbang */
        margin: 0 auto; /* Tengah gambar */
    }

    .flow-section {
        margin-bottom: 5rem;
    }
    .flow-card:not(:first-child)::before {
        content: '';
        position: absolute;
        top: 50px; 
        right: 50%;
        width: 100%;
    
        /* REVISI DI SINI */
        height: 10px; /* Sesuaikan dengan tinggi gambar garis Anda */
        background-image: url('{{ asset('images/garis.png') }}');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
    
        /*z-index: -1;*/
    }
    .flow-card img {
        max-height: 90px; /* Ukuran ikon alur */
        margin-bottom: 1rem;
    }
    .flow-card h4 {
        font-weight: bold;
        color: #333;
    }

    .stats-card {
        background-image: url('{{ asset('images/Union.png') }}'); /* Gambar background untuk statistik */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        color: white;
        padding: 5rem 2rem; /* Padding lebih besar */
        text-align: center;
        border-radius: 1rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        margin-bottom: 5rem; /* Memberi jarak ke footer */
    }
    .stats-card h3 {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 2rem;
    }
    .stats-card h1 {
        font-size: 4.5rem; /* Ukuran angka lebih besar */
        font-weight: bolder;
        margin-bottom: 0.5rem;
    }
    .stats-card p {
        font-size: 1.2rem;
        margin-bottom: 0;
    }

    /* Responsif untuk layar kecil */
    @media (max-width: 768px) {
        .hero-section {
            padding: 5rem 1rem;
        }
        .hero-title-container img {
            height: 40px;
        }
        .hero-title-container h2 {
            font-size: 1.2rem;
        }
        body {
            background: #f0f2f5; /* Nonaktifkan gradasi di mobile jika tidak cocok */
        }
        .intro-image-right-wrapper {
            position: relative; /* Ubah ke relatif di mobile */
            text-align: center;
            margin-top: 2rem;
        }
        .intro-megaphone-img {
            position: static; /* Hilangkan posisi absolut di mobile */
            width: 70px;
            margin-top: 1rem;
            transform: none;
        }
    }
</style>
@endpush

@section('content')
    {{-- Bagian Hero --}}
    <div class="hero-section">
        <div class="hero-title-container">
            <img src="{{ asset('images/logo laporunair.png') }}" alt="Lapor Unair Logo" class="img-fluid">
            <h2 class="text-white">Lapor Segera, Nyaman Bersama</h2>
        </div>
    </div>

    {{-- Bagian Deskripsi & Tombol Aksi --}}
    <div class="description-section">
        <div class="row align-items-center">
            <div class="col-md-4 text-center">
                <img src="{{ asset('images/image.png') }}" class="img-fluid image-card" alt="Gerbang UNAIR">
            </div>
            <div class="col-md-8 px-4">
                <p class="lead text-dark">LaporUnair adalah layanan digital Universitas Airlangga untuk mempermudah civitas akademika dalam melaporkan berbagai kendala di kampus, mulai dari kerusakan sarana-prasarana, kendala persuratan, hingga keluhan akademik. Sistem ini memastikan laporan tersampaikan langsung ke unit kerja terkait, dapat dipantau statusnya secara real-time, serta ditangani dengan lebih cepat dan transparan.</p>
                <a href="{{ route('reports.create') }}" class="btn btn-primary btn-lg rounded-pill px-4 py-2">Buat Laporan Baru</a>
            </div>
        </div>
    </div>

    {{-- Bagian Alur Pelaporan --}}
    <div class="flow-section">
        <h2 class="text-center mb-5 fw-bold">Alur Pelaporan</h2>
        <div class="row text-center justify-content-center">
            <div class="col-md-3">
                <div class="flow-card">
                    <img src="{{ asset('images/padi (7) 1.png') }}" alt="Kirim Laporan" class="img-fluid mb-3">
                    <h4>Kirim Laporan</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="flow-card">
                    <img src="{{ asset('images/padi (6) 1.png') }}" alt="Laporan Diproses" class="img-fluid mb-3">
                    <h4>Laporan Diproses</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="flow-card">
                    <img src="{{ asset('images/padi (5) 1.png') }}" alt="Masalah Selesai" class="img-fluid mb-3">
                    <h4>Masalah Selesai</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="flow-card">
                    <img src="{{ asset('images/padi (4) 1.png') }}" alt="Nilai Pelayanan" class="img-fluid mb-3">
                    <h4>Nilai Pelayanan</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Bagian Statistik Laporan --}}
    <div class="stats-card">
        <h3 class="mb-4">Jumlah Laporan Saat Ini</h3>
        <div class="row justify-content-center">
            <div class="col-md-3 col-6 mb-3"><h1>{{ $pendingCount }}</h1><p>Terkirim</p></div>
            <div class="col-md-3 col-6 mb-3"><h1>{{ $inProgressCount }}</h1><p>Dalam Proses</p></div>
            <div class="col-md-3 col-6 mb-3"><h1>{{ $completedCount }}</h1><p>Selesai</p></div>
        </div>
    </div>
@endsection