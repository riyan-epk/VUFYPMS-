<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Server Error</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>body { background: linear-gradient(135deg, #b71c1c 0%, #e53935 100%); min-height:100vh; display:flex; align-items:center; }</style>
</head>
<body>
    <div class="container text-center text-white py-5">
        <i class="bi bi-exclamation-triangle-fill" style="font-size:5rem; opacity:0.7;"></i>
        <h1 class="display-1 fw-bold mt-3">500</h1>
        <h4 class="mb-3">Server Error</h4>
        <p class="opacity-75 mb-4">Something went wrong on our end. Please try again later.</p>
        <a href="{{ url('/') }}" class="btn btn-light btn-lg px-4"><i class="bi bi-house-fill me-2"></i>Back to Home</a>
    </div>
</body>
</html>
