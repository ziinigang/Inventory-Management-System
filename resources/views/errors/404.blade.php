<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Page Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center
             justify-content-center min-vh-100">
    <div class="text-center p-4">
        <div style="font-size:5rem;color:#94a3b8">
            <i class="bi bi-search"></i>
        </div>
        <h1 class="fw-bold mt-2" style="font-size:4rem;color:#0f172a">404</h1>
        <h5 class="fw-semibold text-dark mb-2">Page Not Found</h5>
        <p class="text-muted mb-4">
            The page you're looking for doesn't exist<br>
            or may have been moved.
        </p>
        <a href="{{ route('dashboard') }}"
           class="btn btn-dark px-4">
            <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
</body>
</html>