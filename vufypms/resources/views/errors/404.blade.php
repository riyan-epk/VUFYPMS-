<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Page Not Found</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: linear-gradient(135deg, #1e3a5f 0%, #1565C0 100%); min-height: 100vh; display: flex; align-items: center; }
    </style>
</head>
<body>
    <div class="container text-center text-white py-5">
        <i class="bi bi-search" style="font-size:5rem; opacity:0.7;"></i>
        <h1 class="display-1 fw-bold mt-3">404</h1>
        <h4 class="mb-3">Page Not Found</h4>
        <p class="opacity-75 mb-4">The page you are looking for doesn't exist or has been moved.</p>
        <a href="{{ url('/') }}" class="btn btn-light btn-lg px-4">
            <i class="bi bi-house-fill me-2"></i>Back to Home
        </a>
    </div>
</body>
</html>
