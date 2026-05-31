<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Inventory Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .login-card .brand-icon {
            font-size: 2.5rem;
            color: #1e293b;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <div class="brand-icon"><i class="bi bi-box-seam"></i></div>
        <h4 class="fw-bold mt-2 mb-0">Inventory Management</h4>
        <p class="text-muted small">Sign in to your account</p>
    </div>

    {{-- Error messages --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-circle me-1"></i>
            {{ $errors->first() }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="/login">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-medium">Email address</label>
            <input
                type="email"
                name="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}"
                placeholder="admin@example.com"
                required
                autofocus
            >
        </div>

        <div class="mb-4">
            <label class="form-label fw-medium">Password</label>
            <input
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="••••••••"
                required
            >
        </div>

        <button type="submit" class="btn btn-dark w-100 py-2 fw-medium">
            <i class="bi bi-box-arrow-in-right me-2"></i>Sign in
        </button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>