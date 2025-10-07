@extends('layouts.app')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Daftar Laporan Kerusakan</h2>
                <a class="btn btn-success" href="{{ route('reports.create') }}">Buat Laporan Baru</a>
            </div>
        </div>
        <div class="card-body">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th width="200px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $report)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $report->title }}</td>
                            <td>{{ $report->location }}</td>
                            <td><span class="badge bg-warning text-dark">{{ Str::title(str_replace('_', ' ', $report->status)) }}</span></td>
                            <td>
                                <form action="{{ route('reports.destroy',$report->report_id) }}" method="POST">
                                    <a class="btn btn-info btn-sm" href="{{ route('reports.show',$report->report_id) }}">Lihat</a>
                                    <a class="btn btn-primary btn-sm" href="{{ route('reports.edit',$report->report_id) }}">Edit</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada laporan yang dibuat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Link Pagination --}}
            <div class="d-flex justify-content-center">
                {!! $reports->links() !!}
            </div>
        </div>
    </div>
@endsection