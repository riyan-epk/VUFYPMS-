<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — VUFYPMS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: linear-gradient(135deg, #1e3a5f 0%, #1565C0 100%); min-height: 100vh; display:flex; align-items:center; padding: 2rem 0; }
        .card { border: none; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .card-header { background: #1e3a5f; border-radius: 16px 16px 0 0; padding: 1.5rem; }
        .form-control:focus { border-color: #1565C0; box-shadow: 0 0 0 0.2rem rgba(21,101,192,0.25); }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card">
                <div class="card-header text-center">
                    <i class="bi bi-mortarboard-fill text-white" style="font-size:2rem;"></i>
                    <h5 class="text-white fw-bold mb-0 mt-1">Student Registration</h5>
                    <p class="text-white-50 small mb-0">VUFYPMS — Virtual University</p>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li class="small">{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.post') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Muhammad Ali Khan" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="bc200400001@vu.edu.pk" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">VU Student ID <span class="text-danger">*</span></label>
                            <input type="text" name="vu_id" class="form-control @error('vu_id') is-invalid @enderror" value="{{ old('vu_id') }}" placeholder="BC200400001" required>
                            <div class="form-text">Your VU registration number (e.g., BC200400001)</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Phone Number</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="+92 300 1234567">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimum 8 characters" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" style="background:#1e3a5f;border:none;">
                            <i class="bi bi-person-plus me-2"></i>Create Account
                        </button>
                    </form>
                    <hr class="my-3">
                    <p class="text-center text-muted small mb-0">
                        Already have an account? <a href="{{ route('login') }}" class="text-primary fw-semibold">Sign In</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
