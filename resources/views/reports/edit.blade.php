@extends(Auth::user()->isAdmin() ? 'layouts.admin' : 'layouts.app')

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

                @php
                    $isAdmin = Auth::user()->isAdmin();
                @endphp

                <div class="row">
                    {{-- Semua field ini sekarang bisa diedit oleh user, tapi disabled untuk admin --}}
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Judul Laporan</label>
                        <input type="text" name="title" value="{{ old('title', $report->title) }}" class="form-control" placeholder="Judul" {{ $isAdmin ? 'disabled' : '' }}>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="location" class="form-label">Lokasi</label>
                        <input type="text" name="location" value="{{ old('location', $report->location) }}" class="form-control" placeholder="Lokasi" {{ $isAdmin ? 'disabled' : '' }}>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select name="category_id" class="form-select" {{ $isAdmin ? 'disabled' : '' }}>
                            @foreach ($categories as $category)
                                <option value="{{ $category->category_id }}" {{ old('category_id', $report->category_id) == $category->category_id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="instansi_id" class="form-label">Instansi</label>
                        <select name="instansi_id" class="form-select" {{ $isAdmin ? 'disabled' : '' }}>
                            @foreach ($instansis as $instansi)
                                <option value="{{ $instansi->instansi_id }}" {{ old('instansi_id', $report->instansi_id) == $instansi->instansi_id ? 'selected' : '' }}>
                                    {{ $instansi->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" style="height:150px" name="description" placeholder="Deskripsi" {{ $isAdmin ? 'disabled' : '' }}>{{ old('description', $report->description) }}</textarea>
                    </div>
                    
                    {{-- Bagian ini akan tampil berbeda untuk Admin dan User --}}
                    @if($isAdmin)
                        {{-- HANYA ADMIN YANG MELIHAT INI --}}
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label fw-bold">Ubah Status</label>
                            <select name="status" class="form-select">
                                <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $report->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $report->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="admin_comment" class="form-label fw-bold">Tambah Komentar / Catatan (Opsional)</label>
                            <textarea class="form-control" name="admin_comment" placeholder="Tambahkan catatan untuk user..."></textarea>
                        </div>
                        
                        {{-- Field untuk menyimpan catatan penyelesaian dan bukti (hanya saat completed) --}}
                        <div class="col-md-12 mb-3" id="completionFields" style="{{ $report->status == 'completed' ? '' : 'display:none;' }}">
                            <label for="completion_notes" class="form-label fw-bold">Completion Notes</label>
                            <textarea class="form-control" name="completion_notes" placeholder="Tambahkan catatan penyelesaian untuk user...">{{ old('completion_notes', $report->completion_notes ?? '') }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3" id="completionImageField" style="{{ $report->status == 'completed' ? '' : 'display:none;' }}">
                            <label for="completion_image" class="form-label">Bukti Penyelesaian (Opsional)</label>
                            <input class="form-control" type="file" name="completion_image">
                        </div>
                    @else
                        {{-- HANYA USER BIASA YANG MELIHAT INI --}}
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
    <script>
        // Tampilkan atau sembunyikan field completion saat nilai status berubah
        (function(){
            var statusSelect = document.querySelector('select[name="status"]');
            if (!statusSelect) return;
            var completionFields = document.getElementById('completionFields');
            var completionImageField = document.getElementById('completionImageField');

            function toggleCompletionFields() {
                var val = statusSelect.value;
                if (val === 'completed') {
                    if (completionFields) completionFields.style.display = '';
                    if (completionImageField) completionImageField.style.display = '';
                } else {
                    if (completionFields) completionFields.style.display = 'none';
                    if (completionImageField) completionImageField.style.display = 'none';
                }
            }

            statusSelect.addEventListener('change', toggleCompletionFields);
            // initial
            toggleCompletionFields();
        })();
    </script>
@endsection