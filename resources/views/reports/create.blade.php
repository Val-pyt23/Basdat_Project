@extends('layouts.app')

@push('styles')
<style>
    .form-container {
        background-color: #ffffff;
        border: 1px solid #d1e7fd;
        border-radius: 15px;
        padding: 2rem;
        max-width: 800px;
        margin: 2rem auto;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .form-container h2 {
        text-align: center;
        margin-bottom: 2rem;
        font-weight: bold;
    }
    .form-label {
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #ced4da;
    }
    .btn-submit {
        background-color: #0d6efd;
        border: none;
        border-radius: 25px;
        padding: 10px 30px;
        float: right;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="form-container">
        <h2>Pembuatan Laporan</h2>
        
        <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori Laporan</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option selected disabled value="">Pilih Kategori...</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->category_id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="instansi_type" class="form-label">Tipe Instansi</label>
                <select class="form-select" id="instansi_type">
                    <option selected disabled value="">Pilih Tipe Instansi...</option>
                    <option value="fakultas">Fakultas</option>
                    <option value="perpustakaan">Perpustakaan</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="instansi_id" class="form-label">Instansi</label>
                <select class="form-select" id="instansi_id" name="instansi_id" required>
                    <option selected disabled value="">Pilih Tipe Instansi terlebih dahulu</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Judul Laporan</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Lokasi (Contoh: Ruang 6.09, Gedung Nano)</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="attachment" class="form-label">Lampirkan File (Opsional)</label>
                <input class="form-control" type="file" id="attachment" name="attachment">
            </div>

            <button type="submit" class="btn btn-primary btn-submit">Kirim Laporan</button>
        </form>
    </div>
</div>

{{-- JAVASCRIPT UNTUK DEPENDENT DROPDOWN --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.getElementById('instansi_type');
        const instansiSelect = document.getElementById('instansi_id');

        typeSelect.addEventListener('change', function () {
            const selectedType = this.value;
            instansiSelect.innerHTML = '<option selected disabled value="">Memuat...</option>';

            if (!selectedType) {
                instansiSelect.innerHTML = '<option selected disabled value="">Pilih Tipe Instansi terlebih dahulu</option>';
                return;
            }

            fetch(`/api/instansi?type=${selectedType}`)
                .then(response => response.json())
                .then(data => {
                    instansiSelect.innerHTML = '<option selected disabled value="">Pilih Instansi Terkait...</option>';
                    
                    data.forEach(function (instansi) {
                        const option = document.createElement('option');
                        option.value = instansi.instansi_id;
                        option.textContent = instansi.name;
                        instansiSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching instansi:', error);
                    instansiSelect.innerHTML = '<option selected disabled value="">Gagal memuat data</option>';
                });
        });
    });
</script>
@endsection