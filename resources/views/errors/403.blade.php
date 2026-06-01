<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Access Denied</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center
             justify-content-center min-vh-100">
    <div class="text-center p-4">
        <div style="font-size:5rem;color:#f59e0b">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        <h1 class="fw-bold mt-2" style="font-size:4rem;color:#0f172a">403</h1>
        <h5 class="fw-semibold text-dark mb-2">Access Denied</h5>
        <p class="text-muted mb-4">
            You don't have permission to access this page.<br>
            This area is restricted to administrators only.
        </p>
        <a href="{{ route('dashboard') }}"
           class="btn btn-dark px-4">
            <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
</body>
</html>