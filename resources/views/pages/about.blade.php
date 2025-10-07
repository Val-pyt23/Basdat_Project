@extends('layouts.app')

{{-- Menambahkan CSS khusus untuk halaman ini --}}
@push('styles')
<style>
    /* Styling untuk Intro Section menggunakan Flexbox */
    .intro-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem; /* Memberi jarak antar elemen */
        margin-bottom: 4rem;
    }
    .intro-image-left, .intro-image-right-wrapper {
        flex: 0 0 25%; /* Alokasi 25% lebar untuk gambar di sisi */
        text-align: center;
    }
    .intro-text {
        flex: 1; /* Teks akan mengambil sisa ruang yang tersedia */
    }
    .intro-image-right-wrapper {
        position: relative; /* Diperlukan untuk menempatkan megaphone */
    }
    .intro-megaphone-img {
        position: absolute;
        top: -20px;
        right: -30px; /* Posisikan megaphone sedikit keluar dari logo */
        width: 80px; /* Ukuran megaphone */
    }

    /* Styling untuk FAQ bubbles */
    .faq-bubble {
        padding: 1rem 1.5rem;
        border-radius: 1.5rem;
        margin-bottom: 1rem;
        position: relative;
        max-width: 70%;
    }
    .faq-question {
        background-color: #ffc107; /* Warna kuning */
        color: #333;
        border-bottom-left-radius: 0;
    }
    .faq-answer {
        background-color: #0d6efd; /* Warna biru */
        color: white;
        border-top-right-radius: 0;
    }
    .faq-question::after, .faq-answer::after {
        content: '';
        position: absolute;
        border-style: solid;
    }
    .faq-question::after {
        border-width: 15px 15px 0 0;
        border-color: #ffc107 transparent transparent transparent;
        bottom: 0;
        left: -15px;
    }
    .faq-answer::after {
        border-width: 0 0 15px 15px;
        border-color: transparent transparent #0d6efd transparent;
        top: 0;
        right: -15px;
    }
    
    /* Responsivitas untuk mobile */
    @media (max-width: 768px) {
        .intro-container {
            flex-direction: column; /* Ubah layout jadi vertikal di layar kecil */
        }
    }
</style>
@endpush

@section('content')
    <h1 class="text-center mb-5">Tentang LaporUnair</h1>

    {{-- Bagian Intro dengan struktur Flexbox --}}
    <div class="intro-container">
        {{-- Gambar Komputer di Kiri --}}
        <div class="intro-image-left d-none d-md-block">
            <img src="{{ asset('images/computer.png') }}" alt="Ilustrasi Komputer" class="img-fluid">
        </div>

        {{-- Teks di Tengah --}}
        <div class="intro-text text-center">
            <p class="lead">LaporUnair adalah layanan digital Universitas Airlangga untuk mempermudah civitas akademika dalam melaporkan berbagai kendala di kampus, mulai dari kerusakan sarana-prasarana, kendala persuratan, hingga keluhan akademik. Sistem ini memastikan setiap laporan akan tersampaikan langsung ke unit kerja terkait, dapat dipantau statusnya secara real-time, serta ditangani dengan lebih cepat dan transparan.</p>
        </div>
        
        {{-- Gambar Logo & Megaphone di Kanan --}}
        <div class="intro-image-right-wrapper d-none d-md-block">
            <img src="{{ asset('images/logo laporunair.png') }}" alt="Logo Lapor Unair" class="img-fluid">
            <img src="{{ asset('images/image 12.png') }}" alt="Megaphone Icon" class="intro-megaphone-img">
        </div>
    </div>

    <hr class="my-5">

    {{-- Bagian FAQ --}}
    <h2 class="text-center mb-5">Frequently Asked Questions</h2>
    <div class="row justify-content-center">
        <div class="col-md-10">
            {{-- FAQ 1 --}}
            <div class="d-flex justify-content-start mb-4">
                <div class="faq-bubble faq-question">Apa itu LaporUnair?</div>
            </div>
            <div class="d-flex justify-content-end mb-4">
                <div class="faq-bubble faq-answer">LaporUnair adalah layanan digital Universitas Airlangga yang memfasilitasi civitas akademika untuk melaporkan berbagai kendala di kampus...</div>
            </div>

            {{-- ... Sisa FAQ lainnya ... --}}
        </div>
    </div>
@endsection