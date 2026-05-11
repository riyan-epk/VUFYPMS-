<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — VUFYPMS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: linear-gradient(135deg, #1e3a5f 0%, #1565C0 100%); min-height: 100vh; display: flex; align-items: center; }
        .login-card { border: none; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .login-card .card-header { background: #1e3a5f; border-radius: 16px 16px 0 0; padding: 2rem; text-align: center; }
        .form-control:focus { border-color: #1565C0; box-shadow: 0 0 0 0.2rem rgba(21,101,192,0.25); }
        .btn-login { background: #1e3a5f; border: none; padding: 0.75rem; font-weight: 600; }
        .btn-login:hover { background: #1565C0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card login-card">
                    <div class="card-header">
                        <div class="mb-2"><i class="bi bi-mortarboard-fill text-white" style="font-size:2.5rem;"></i></div>
                        <h4 class="text-white mb-0 fw-bold">VUFYPMS</h4>
                        <p class="text-white-50 mb-0 small">Virtual University FYP Management System</p>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 text-center">Sign In to Your Account</h5>

                        @if(session('success'))
                            <div class="alert alert-success alert-sm"><i class="bi bi-check-circle me-1"></i>{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-sm"><i class="bi bi-x-circle me-1"></i>{{ session('error') }}</div>
                        @endif

                        <form method="POST" action="{{ route('login.post') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="your@vu.edu.pk" required autofocus>
                                </div>
                                @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePwd(this)"><i class="bi bi-eye"></i></button>
                                </div>
                                @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                    <label class="form-check-label small" for="remember">Remember me</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-login w-100 text-white">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                            </button>
                        </form>

                        <hr class="my-3">
                        <p class="text-center text-muted small mb-0">
                            New student? <a href="{{ route('register') }}" class="text-primary fw-semibold">Create Account</a>
                        </p>
                        <p class="text-center text-muted small mt-2 mb-0">
                            <a href="{{ route('home') }}" class="text-muted"><i class="bi bi-arrow-left me-1"></i>Back to Home</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePwd(btn) {
            const input = btn.previousElementSibling;
            const icon = btn.querySelector('i');
            if (input.type === 'password') { input.type = 'text'; icon.className = 'bi bi-eye-slash'; }
            else { input.type = 'password'; icon.className = 'bi bi-eye'; }
        }
    </script>
</body>
</html>
