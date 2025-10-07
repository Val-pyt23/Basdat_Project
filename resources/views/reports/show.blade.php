{{-- REVISI: Gunakan pengecekan peran yang lebih fleksibel untuk memilih layout --}}
@extends(in_array(Auth::user()->role->name, ['superadmin', 'admin_instansi']) ? 'layouts.admin' : 'layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Detail Laporan</h2>
            <div>
                @if(in_array(Auth::user()->role->name, ['superadmin', 'admin_instansi']))
                    <a class="btn btn-primary" href="{{ route('reports.edit', $report->report_id) }}">
                        Edit Laporan
                    </a>
                @endif
                <a class="btn btn-secondary ms-2" href="{{ in_array(Auth::user()->role->name, ['superadmin', 'admin_instansi']) ? route('dashboard') : route('reports.index') }}"> Kembali</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h3 class="card-title mb-3">{{ $report->title }}</h3>
                    <p class="card-text">{{ $report->description }}</p>
                </div>
                <div class="col-md-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Status:</strong>
                            <span class="badge bg-warning text-dark">{{ Str::title(str_replace('_', ' ', $report->status)) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Kategori:</strong>
                            <span>{{ $report->category->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Instansi:</strong>
                            <span>{{ $report->instansi->name ?? 'N/A' }}</span>
                        </li>
                         <li class="list-group-item d-flex justify-content-between">
                            <strong>Lokasi:</strong>
                            <span>{{ $report->location }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Pelapor:</strong>
                            <span>{{ $report->reporter->username ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Tanggal:</strong>
                            <span>{{ $report->created_at->format('d M Y, H:i') }}</span>
                        </li>
                        @if($report->attachment_path)
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Lampiran:</strong>
                                <a href="{{ Storage::url($report->attachment_path) }}" target="_blank">Lihat Lampiran</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- BAGIAN KOMENTAR --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="mb-0">Diskusi / Tindak Lanjut</h4>
        </div>
        <div class="card-body">
            @forelse($report->comments as $comment)
                <div class="d-flex mb-3">
                    <div class="flex-shrink-0 me-3">
                        <img src="https://via.placeholder.com/50" alt="Avatar" class="rounded-circle">
                    </div>
                    <div class="flex-grow-1 border-bottom pb-2">
                        <h5 class="mt-0 mb-1">
                            {{ $comment->user->username }}
                            @if(in_array($comment->user->role->name, ['superadmin', 'admin_instansi']))
                                <span class="badge bg-primary">Admin</span>
                            @endif
                        </h5>
                        <p class="mb-1">{{ $comment->body }}</p>
                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted">Belum ada komentar.</p>
            @endforelse

            <hr class="my-4">

            {{-- Form Tambah Komentar --}}
            <form action="{{ route('comments.store', $report->report_id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="body" class="form-label">Tulis Balasan</label>
                    <textarea name="body" id="body" rows="3" class="form-control" placeholder="Tulis komentar Anda di sini..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Kirim Komentar</button>
            </form>
        </div>
    </div>

    {{-- BAGIAN RATING (MUNCUL JIKA LAPORAN COMPLETED) --}}
    @if($report->status == 'completed')
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-white">
            <h4 class="mb-0">Beri Penilaian</h4>
        </div>
        <div class="card-body">
            @if(Auth::id() == $report->user_id)
                @if($existingRating)
                    {{-- Tampilkan rating yang sudah diberikan --}}
                    <div class="text-center">
                        <p class="mb-1">Anda telah memberikan penilaian:</p>
                        <h3 class="display-5">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $existingRating->rating_value)
                                    <span style="color: #ffc107;">★</span>
                                @else
                                    <span style="color: #e0e0e0;">★</span>
                                @endif
                            @endfor
                        </h3>
                        @if($existingRating->comment)
                            <p class="mt-2 fst-italic">"{{ $existingRating->comment }}"</p>
                        @endif
                    </div>
                @else
                    {{-- Tampilkan form untuk memberi rating --}}
                    <form action="{{ route('ratings.store', $report->report_id) }}" method="POST">
                        {{-- ... (Isi form rating) ... --}}
                    </form>
                @endif
            @else
                <p class="text-center text-muted">Hanya pelapor yang dapat memberikan penilaian.</p>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection