<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Inventory Management System' }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; }
        .sidebar {
            min-height: 100vh;
            background-color: #1e293b;
            width: 250px;
        }
        .sidebar .nav-link {
            color: #94a3b8;
            padding: 10px 20px;
            border-radius: 6px;
            margin: 2px 8px;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #334155;
            color: #ffffff;
        }
        .sidebar .brand {
            color: #ffffff;
            font-weight: 700;
            font-size: 1.1rem;
            padding: 20px;
            border-bottom: 1px solid #334155;
        }
        .main-content { flex: 1; padding: 24px; }
        .top-bar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 24px;
        }
    </style>
</head>
<body>

<div class="d-flex">

    {{-- Sidebar --}}
    <div class="sidebar d-flex flex-column">
        <div class="brand">
            <i class="bi bi-box-seam me-2"></i>Inventory MS
        </div>

        <nav class="nav flex-column mt-3 flex-grow-1">
            <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </x-nav-link>

          <x-nav-link href="{{ route('products.index') }}" :active="request()->routeIs('products.*')">
                <i class="bi bi-box me-2"></i>Products
            </x-nav-link> 

           {{-- <x-nav-link href="{{ route('suppliers.index') }}" :active="request()->routeIs('suppliers.*')">
                <i class="bi bi-truck me-2"></i>Suppliers 
            </x-nav-link> --}}

          {{--  <x-nav-link href="{{ route('inventory.index') }}" :active="request()->routeIs('inventory.*')">
                <i class="bi bi-clipboard-data me-2"></i>Inventory
            </x-nav-link> --}}

           {{-- <x-nav-link href="{{ route('stock-movements.index') }}" :active="request()->routeIs('stock-movements.*')">
                <i class="bi bi-arrow-left-right me-2"></i>Stock Movements
            </x-nav-link>   --}}

            @if(auth()->user()->isAdmin())
         {{--   <x-nav-link href="{{ route('reports.index') }}" :active="request()->routeIs('reports.*')">
                <i class="bi bi-file-earmark-bar-graph me-2"></i>Reports
            </x-nav-link> --}}
            @endif
        </nav>

        {{-- User info + logout at bottom --}}
        <div class="p-3 border-top" style="border-color: #334155 !important;">
            <div class="text-secondary small mb-2">
                <i class="bi bi-person-circle me-1"></i>
                {{ auth()->user()->name }}
                <span class="badge bg-secondary ms-1">{{ auth()->user()->role }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                </button>
            </form>
        </div>
    </div>

    {{-- Main area --}}
    <div class="flex-grow-1 d-flex flex-column">

        {{-- Top bar --}}
        <div class="top-bar d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-semibold text-dark">{{ $title ?? 'Dashboard' }}</h6>
            <small class="text-muted">{{ now()->format('F d, Y') }}</small>
        </div>

        {{-- Flash messages --}}
        <div class="main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>