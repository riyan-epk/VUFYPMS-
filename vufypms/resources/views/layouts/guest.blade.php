<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'VUFYPMS') — Virtual University FYP System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('styles')
    <style>
        :root { --vu-dark: #1e3a5f; --vu-primary: #1565C0; }
        body { background: #f0f4f8; font-family: 'Segoe UI', sans-serif; }
        .navbar-vu { background: var(--vu-dark) !important; }
        .navbar-vu .navbar-brand { color: #fff !important; font-weight: 700; font-size: 1.2rem; }
        .navbar-vu .nav-link { color: rgba(255,255,255,0.8) !important; }
        .navbar-vu .nav-link:hover, .navbar-vu .nav-link.active { color: #fff !important; }
        .hero-section { background: linear-gradient(135deg, var(--vu-dark) 0%, #1565C0 100%); padding: 5rem 0; }
        .card { border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.08); border-radius: 12px; }
        footer { background: var(--vu-dark); color: rgba(255,255,255,0.7); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-vu sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-mortarboard-fill me-2"></i>VUFYPMS
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon" style="filter:invert(1)"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('announcements') ? 'active' : '' }}" href="{{ route('announcements') }}">Announcements</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('projects.search') ? 'active' : '' }}" href="{{ route('projects.search') }}">Projects</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('guidelines') ? 'active' : '' }}" href="{{ route('guidelines') }}">Guidelines</a></li>
                </ul>
                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-light btn-sm">Register</a>
                </div>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible m-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible m-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')

    <footer class="py-4 mt-5">
        <div class="container text-center">
            <p class="mb-1"><strong class="text-white">VUFYPMS</strong> — Virtual University Final Year Project Management System</p>
            <p class="mb-0" style="font-size:0.82rem;">&copy; {{ date('Y') }} Virtual University of Pakistan. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @stack('scripts')
</body>
</html>
