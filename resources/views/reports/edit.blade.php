{{-- REVISI 1: Gunakan pengecekan peran yang lebih fleksibel untuk memilih layout --}}
@extends(in_array(Auth::user()->role->name, ['superadmin', 'admin_instansi']) ? 'layouts.admin' : 'layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Edit Laporan</h2>
            <a class="btn btn-secondary" href="{{ in_array(Auth::user()->role->name, ['superadmin', 'admin_instansi']) ? route('dashboard') : route('reports.index') }}"> Kembali</a>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Terjadi kesalahan pada input Anda.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('reports.update', $report->report_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- REVISI 2: Definisikan variabel $isAdmin dengan cara yang benar --}}
                @php
                    $isAdmin = in_array(Auth::user()->role->name, ['superadmin', 'admin_instansi']);
                @endphp

                <div class="row">
                    {{-- REVISI 3: Pastikan semua field menggunakan $isAdmin untuk disabled --}}
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Judul Laporan</label>
                        <input type="text" name="title" value="{{ $report->title }}" class="form-control" {{ $isAdmin ? 'disabled' : '' }}>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="location" class="form-label">Lokasi</label>
                        <input type="text" name="location" value="{{ $report->location }}" class="form-control" {{ $isAdmin ? 'disabled' : '' }}>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select name="category_id" class="form-select" {{ $isAdmin ? 'disabled' : '' }}>
                            <option>{{ $report->category->name }}</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="instansi_id" class="form-label">Instansi</label>
                        <select name="instansi_id" class="form-select" {{ $isAdmin ? 'disabled' : '' }}>
                            <option>{{ $report->instansi->name ?? 'N/A' }}</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" style="height:150px" name="description" {{ $isAdmin ? 'disabled' : '' }}>{{ $report->description }}</textarea>
                    </div>
                    
                    {{-- Bagian ini akan tampil berbeda untuk Admin dan User --}}
                    @if($isAdmin)
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label fw-bold">Ubah Status</label>
                            <select name="status" class="form-select">
                                <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $report->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $report->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="admin_comment" class="form-label fw-bold">Tambah Komentar / Catatan (Opsional)</label>
                            <textarea class="form-control" name="admin_comment" placeholder="Tambahkan catatan untuk user..."></textarea>
                        </div>
                    @else
                        <div class="col-md-6 mb-3">
                            <label for="attachment" class="form-label">Ganti Lampiran (Opsional)</label>
                            <input class="form-control" type="file" name="attachment">
                        </div>
                    @endif
                    
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection