<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Lapor Unair</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body style="background-color: #f8f9fa;">
    
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/logo laporunair.png') }}" alt="Lapor Unair Logo" height="40">
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Daftar Laporan</a></li>
                </ul>
                <div class="d-flex align-items-center">

                    <div class="dropdown me-3">
                        <a href="#" class="text-secondary position-relative fs-4 text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                            🔔
                            @if(isset($notifications) && $notifications->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6em;">
                                    {{ $notifications->count() }}
                                </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" style="width: 350px;">
                            <li class="px-3 py-2"><strong>Notifikasi</strong></li>
                            <li><hr class="dropdown-divider"></li>
                            @if(isset($notifications))
                                @forelse($notifications as $notification)
                                    <li>
                                        {{-- REVISI: tambahkan kelas dan data-id untuk AJAX mark-as-read --}}
                                        <a class="dropdown-item notification-link" href="{{ $notification->data['url'] }}?notify_id={{ $notification->id }}" data-id="{{ $notification->id }}" data-url="{{ $notification->data['url'] }}?notify_id={{ $notification->id }}" style="white-space: normal;">
                                            <p class="mb-0 small">{{ $notification->data['message'] }}</p>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </a>
                                    </li>
                                @empty
                                    <li class="text-center p-2 text-muted small">Tidak ada notifikasi baru.</li>
                                @endforelse
                            @endif
                        </ul>
                    </div>
                    
                    <div class="dropdown">
                        <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('images/profile-icon.png') }}" alt="mdo" width="32" height="32" class="rounded-circle">
                        </a>
                        <ul class="dropdown-menu text-small dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                        Logout
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="footer mt-auto py-3 bg-dark text-white text-center">
        <div class="container">
            <span>© 2025 LaporUnair. All Rights Reserved.</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            document.querySelectorAll('.notification-link').forEach(function (el) {
                el.addEventListener('click', function (e) {
                    e.preventDefault();
                    const id = el.dataset.id;
                    const url = el.dataset.url || el.getAttribute('href');
                    fetch(`/notifications/${id}/read`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({})
                    }).catch(function () {
                    }).finally(function () {
                        window.location = url;
                    });
                });
            });
        });
    </script>
</body>
</html>