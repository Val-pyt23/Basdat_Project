@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h2 class="text-center mb-4">Daftar Laporan</h2>

            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No. Laporan</th>
                            <th>Nama Pelapor</th>
                            <th>Kategori</th>
                            <th>Instansi</th>
                            <th>Judul Laporan</th>
                            <th>Lokasi</th>
                            <th>Tanggal Laporan</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $report)
                        <tr>
                            <td>#{{ $report->report_id }}</td>
                            <td>{{ $report->reporter->username ?? 'N/A' }}</td>
                            <td>{{ $report->category->name }}</td>
                            <td>{{ $report->instansi->name ?? 'N/A' }}</td>
                            <td>{{ Str::limit($report->title, 20) }}</td>
                            <td>{{ $report->location }}</td>
                            <td>{{ $report->created_at->format('d M Y') }}</td>
                            <td>{{ Str::limit($report->description, 30) }}</td>
                            <td><span class="badge bg-warning text-dark">{{ Str::title(str_replace('_', ' ', $report->status)) }}</span></td>
                            <td>
                                {{-- KUMPULKAN SEMUA TOMBOL AKSI DI SINI --}}
                                <form action="{{ route('reports.destroy', $report->report_id) }}" method="POST" class="d-inline-flex">
                                    <a class="btn btn-info btn-sm me-1" href="{{ route('reports.show', $report->report_id) }}">Lihat</a>
                                    <a class="btn btn-primary btn-sm me-1" href="{{ route('reports.edit', $report->report_id) }}">Edit</a>
                                    
                                    {{-- TAMBAHKAN FORM HAPUS INI --}}
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">Belum ada laporan yang masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {!! $reports->links() !!}
            </div>
        </div>
    </div>
</div>
@endsection
