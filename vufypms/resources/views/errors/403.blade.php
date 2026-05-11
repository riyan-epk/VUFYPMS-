<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Forbidden</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>body { background: linear-gradient(135deg, #7b1fa2 0%, #4a148c 100%); min-height:100vh; display:flex; align-items:center; }</style>
</head>
<body>
    <div class="container text-center text-white py-5">
        <i class="bi bi-shield-fill-x" style="font-size:5rem; opacity:0.7;"></i>
        <h1 class="display-1 fw-bold mt-3">403</h1>
        <h4 class="mb-3">Access Forbidden</h4>
        <p class="opacity-75 mb-4">You don't have permission to access this resource.</p>
        <a href="{{ url('/') }}" class="btn btn-light btn-lg px-4"><i class="bi bi-house-fill me-2"></i>Back to Home</a>
    </div>
</body>
</html>
