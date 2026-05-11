<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'VUFYPMS') — Virtual University FYP Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    @stack('styles')
    <style>
        :root {
            --vu-dark: #1e3a5f;
            --vu-primary: #1565C0;
            --vu-light: #E3F2FD;
            --sidebar-width: 260px;
        }
        body { background: #f0f4f8; font-family: 'Segoe UI', sans-serif; }
        .sidebar {
            width: var(--sidebar-width); height: 100vh; background: var(--vu-dark);
            position: fixed; top: 0; left: 0; z-index: 1000;
            display: flex; flex-direction: column; overflow: hidden; transition: all 0.3s;
        }
        .sidebar-brand {
            padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1);
            flex-shrink: 0;
        }
        .sidebar-brand h5 { color: #fff; font-weight: 700; font-size: 1.1rem; margin: 0; }
        .sidebar-brand small { color: rgba(255,255,255,0.5); font-size: 0.75rem; }
        .sidebar-nav { padding: 1rem 0; flex: 1; overflow-y: auto; min-height: 0; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 2px; }
        .sidebar-nav .nav-label {
            color: rgba(255,255,255,0.4); font-size: 0.7rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.08em;
            padding: 0.75rem 1.5rem 0.25rem;
        }
        .sidebar-nav .nav-link {
            color: rgba(255,255,255,0.75); padding: 0.65rem 1.5rem;
            display: flex; align-items: center; gap: 0.75rem;
            border-radius: 0; transition: all 0.2s; font-size: 0.9rem;
        }
        .sidebar-nav .nav-link:hover, .sidebar-nav .nav-link.active {
            color: #fff; background: rgba(255,255,255,0.1);
            border-left: 3px solid #42A5F5;
        }
        .sidebar-nav .nav-link i { font-size: 1.05rem; width: 20px; text-align: center; }
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; display: flex; flex-direction: column; }
        .topbar {
            background: #fff; padding: 0.75rem 1.5rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08); display: flex;
            align-items: center; justify-content: between; position: sticky; top: 0; z-index: 900;
        }
        .content-area { padding: 1.5rem; flex: 1; }
        .card { border: none; box-shadow: 0 1px 6px rgba(0,0,0,0.07); border-radius: 12px; }
        .card-header { background: #fff; border-bottom: 1px solid #f0f0f0; font-weight: 600; }
        .stat-card { border-radius: 12px; padding: 1.25rem; color: #fff; }
        .badge-notification { background: #ef4444; color: #fff; border-radius: 50%; width: 18px; height: 18px; font-size: 0.65rem; display: flex; align-items: center; justify-content: center; }
        @media(max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h5><i class="bi bi-mortarboard-fill me-2"></i>VUFYPMS</h5>
            <small>Virtual University FYP System</small>
        </div>
        <nav class="sidebar-nav">
            @yield('sidebar-nav')
        </nav>
        <div class="p-3 border-top" style="border-color: rgba(255,255,255,0.1)!important; flex-shrink:0;">
            <div class="d-flex align-items-center gap-2 mb-2">
                <img src="{{ auth()->user()->profile_photo_url }}" alt="Avatar" class="rounded-circle" width="34" height="34" style="object-fit:cover;">
                <div>
                    <div class="text-white" style="font-size:0.82rem;font-weight:600;">{{ auth()->user()->name }}</div>
                    <div style="color:rgba(255,255,255,0.5);font-size:0.72rem;">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-sm btn-outline-light w-100"><i class="bi bi-box-arrow-right me-1"></i>Logout</button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar d-flex align-items-center justify-content-between w-100">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-outline-secondary d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="bi bi-list"></i>
                </button>
                <h6 class="mb-0 fw-semibold text-muted">@yield('page-title', 'Dashboard')</h6>
            </div>
            <div class="d-flex align-items-center gap-3">
                @php $unread = auth()->user()->unread_notifications_count; @endphp
                <div class="position-relative">
                    <a href="{{ auth()->user()->role === 'student' ? route('student.notifications.index') : (auth()->user()->role === 'supervisor' ? route('supervisor.notifications.index') : '#') }}" class="btn btn-sm btn-light position-relative">
                        <i class="bi bi-bell fs-5"></i>
                        @if($unread > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.6rem;">{{ $unread > 9 ? '9+' : $unread }}</span>
                        @endif
                    </a>
                </div>
                <span class="text-muted d-none d-sm-inline" style="font-size:0.82rem;">{{ now()->format('M d, Y') }}</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible mx-3 mt-3 mb-0 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible mx-3 mt-3 mb-0 d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mx-3 mt-3 mb-0">
                <i class="bi bi-x-circle-fill me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="content-area">
            @yield('content')
        </div>

        <footer class="text-center py-3 text-muted" style="font-size:0.8rem;background:#fff;border-top:1px solid #f0f0f0;">
            &copy; {{ date('Y') }} Virtual University of Pakistan — VUFYPMS. All rights reserved.
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({ responsive: true, pageLength: 15 });
            $('[data-bs-toggle="tooltip"]').tooltip();
            $('form.confirm-delete').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                Swal.fire({ title: 'Are you sure?', text: 'This action cannot be undone.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete it!' })
                    .then(r => { if (r.isConfirmed) form.submit(); });
            });
        });
    </script>
</body>
</html>
