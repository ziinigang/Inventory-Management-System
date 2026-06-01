@props(['title' => 'Inventory Management System'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token"  content="{{ csrf_token() }}">
    <title>{{ $title }} — Inventory MS</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">

    <style>
        :root {
            --sidebar-bg    : #0f172a;
            --sidebar-hover : #1e293b;
            --sidebar-active: #334155;
            --sidebar-text  : #94a3b8;
            --sidebar-white : #f1f5f9;
            --sidebar-width : 250px;
            --topbar-h      : 56px;
        }

        * { box-sizing: border-box; }

        body {
            background : #f1f5f9;
            font-family: system-ui, -apple-system, sans-serif;
            font-size  : 14px;
        }

        /* ── Sidebar ───────────────────────────── */
        .sidebar {
            position  : fixed;
            top       : 0; left: 0;
            width     : var(--sidebar-width);
            height    : 100vh;
            background: var(--sidebar-bg);
            display   : flex;
            flex-direction: column;
            z-index   : 1040;
            transition: transform .25s ease;
        }

        .sidebar-brand {
            padding      : 18px 20px;
            border-bottom: 1px solid #1e293b;
            color        : var(--sidebar-white);
            font-weight  : 700;
            font-size    : 15px;
            text-decoration: none;
            display      : flex;
            align-items  : center;
            gap          : 10px;
            flex-shrink  : 0;
        }

        .sidebar-brand .brand-icon {
            width          : 32px; height: 32px;
            background     : #3b82f6;
            border-radius  : 8px;
            display        : flex;
            align-items    : center;
            justify-content: center;
            font-size      : 16px;
            color          : white;
            flex-shrink    : 0;
        }

        .sidebar-nav {
            flex      : 1;
            overflow-y: auto;
            padding   : 12px 8px;
        }

        .sidebar-nav .nav-section {
            font-size    : 10px;
            font-weight  : 600;
            color        : #475569;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding      : 12px 12px 4px;
        }

        .sidebar-nav .nav-link {
            color        : var(--sidebar-text);
            padding      : 9px 12px;
            border-radius: 8px;
            margin-bottom: 2px;
            display      : flex;
            align-items  : center;
            gap          : 10px;
            font-size    : 13.5px;
            transition   : background .15s, color .15s;
            text-decoration: none;
        }

        .sidebar-nav .nav-link:hover {
            background: var(--sidebar-hover);
            color     : var(--sidebar-white);
        }

        .sidebar-nav .nav-link.active {
            background: var(--sidebar-active);
            color     : #ffffff;
            font-weight: 600;
        }

        .sidebar-nav .nav-link .nav-icon {
            width          : 20px; height: 20px;
            display        : flex;
            align-items    : center;
            justify-content: center;
            font-size      : 15px;
            flex-shrink    : 0;
        }

        .sidebar-footer {
            padding      : 12px;
            border-top   : 1px solid #1e293b;
            flex-shrink  : 0;
        }

        .sidebar-footer .user-info {
            display    : flex;
            align-items: center;
            gap        : 10px;
            padding    : 8px;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .sidebar-footer .user-avatar {
            width          : 34px; height: 34px;
            background     : #3b82f6;
            border-radius  : 50%;
            display        : flex;
            align-items    : center;
            justify-content: center;
            color          : white;
            font-weight    : 700;
            font-size      : 13px;
            flex-shrink    : 0;
        }

        .sidebar-footer .user-name {
            color    : var(--sidebar-white);
            font-size: 13px;
            font-weight: 600;
            line-height: 1.2;
        }

        .sidebar-footer .user-role {
            color    : var(--sidebar-text);
            font-size: 11px;
        }

        /* ── Main content ──────────────────────── */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height : 100vh;
            display    : flex;
            flex-direction: column;
        }

        /* ── Top bar ────────────────────────────── */
        .topbar {
            height    : var(--topbar-h);
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display   : flex;
            align-items: center;
            justify-content: space-between;
            padding   : 0 24px;
            position  : sticky;
            top        : 0;
            z-index    : 100;
            flex-shrink: 0;
        }

        .topbar .page-title {
            font-weight: 600;
            font-size  : 15px;
            color      : #0f172a;
        }

        .topbar .topbar-meta {
            font-size: 12px;
            color    : #94a3b8;
        }

        /* ── Content area ───────────────────────── */
        .content-area {
            padding: 24px;
            flex   : 1;
        }

        /* ── Cards ──────────────────────────────── */
        .stat-card {
            background   : #ffffff;
            border-radius: 12px;
            border       : 1px solid #e2e8f0;
            padding      : 20px;
            display      : flex;
            align-items  : center;
            gap          : 16px;
            transition   : box-shadow .2s;
        }

        .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); }

        .stat-icon {
            width          : 48px; height: 48px;
            border-radius  : 12px;
            display        : flex;
            align-items    : center;
            justify-content: center;
            font-size      : 22px;
            flex-shrink    : 0;
        }

        .stat-value {
            font-size  : 26px;
            font-weight: 700;
            line-height: 1;
            color      : #0f172a;
        }

        .stat-label {
            font-size: 12px;
            color    : #64748b;
            margin-top: 3px;
        }

        /* ── Table cards ────────────────────────── */
        .card {
            border-radius: 12px;
            border       : 1px solid #e2e8f0;
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
            border-bottom: 1px solid #e2e8f0;
            padding      : 14px 20px;
        }

        .table th {
            font-size  : 12px;
            font-weight: 600;
            color      : #64748b;
            text-transform: uppercase;
            letter-spacing: .04em;
            padding    : 10px 16px;
        }

        .table td { padding: 11px 16px; vertical-align: middle; }

        /* ── Alerts ─────────────────────────────── */
        .low-stock-banner {
            background   : #fef3c7;
            border       : 1px solid #f59e0b;
            border-radius: 10px;
            padding      : 12px 16px;
            margin-bottom: 20px;
            display      : flex;
            align-items  : center;
            gap          : 10px;
            font-size    : 13px;
            color        : #92400e;
        }

        /* ── Mobile overlay ──────────────────────── */
        .sidebar-overlay {
            display   : none;
            position  : fixed;
            inset     : 0;
            background: rgba(0,0,0,.5);
            z-index   : 1039;
        }

        /* ── Responsive ─────────────────────────── */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
            }
            .sidebar-overlay.show {
                display: block;
            }
            .content-area {
                padding: 16px;
            }
        }

        /* ── Pagination ─────────────────────────── */
        .pagination .page-link {
            border-radius: 6px !important;
            margin       : 0 2px;
            border       : 1px solid #e2e8f0;
            color        : #475569;
            font-size    : 13px;
        }

        .pagination .page-item.active .page-link {
            background: #0f172a;
            border-color: #0f172a;
        }

        /* ── Badges ─────────────────────────────── */
        .badge { font-weight: 500; font-size: 11px; }
    </style>
</head>
<body>

{{-- Mobile sidebar overlay --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>

{{-- ═══════════════ SIDEBAR ════════════════ --}}
<aside class="sidebar" id="sidebar">

    {{-- Brand --}}
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <div class="brand-icon">
            <i class="bi bi-box-seam"></i>
        </div>
        <span>Inventory MS</span>
    </a>

    {{-- Navigation --}}
    <nav class="sidebar-nav">

        <div class="nav-section">Main</div>

        <a href="{{ route('dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-speedometer2"></i></span>
            Dashboard
        </a>

        <div class="nav-section mt-2">Inventory</div>

        <a href="{{ route('products.index') }}"
           class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-box"></i></span>
            Products
        </a>

        <a href="{{ route('suppliers.index') }}"
           class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-truck"></i></span>
            Suppliers
        </a>

        <a href="{{ route('inventory.index') }}"
           class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-clipboard-data"></i></span>
            Inventory
        </a>

        <a href="{{ route('stock-movements.index') }}"
           class="nav-link {{ request()->routeIs('stock-movements.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-arrow-left-right"></i></span>
            Stock Movements
        </a>

        @if(auth()->user()->isAdmin())
        <div class="nav-section mt-2">Admin</div>

        <a href="{{ route('reports.index') }}"
           class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-file-earmark-bar-graph"></i></span>
            Reports
        </a>
        @endif

    </nav>

    {{-- User footer --}}
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="btn btn-sm w-100 text-start"
                    style="color:#94a3b8;background:none;border:none;
                           padding:8px 12px;border-radius:8px"
                    onmouseover="this.style.background='#1e293b'"
                    onmouseout="this.style.background='none'">
                <i class="bi bi-box-arrow-right me-2"></i>Sign Out
            </button>
        </form>
    </div>

</aside>

{{-- ═══════════════ MAIN WRAPPER ═══════════ --}}
<div class="main-wrapper">

    {{-- Top bar --}}
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            {{-- Mobile hamburger --}}
            <button class="btn btn-sm d-md-none p-1"
                    id="sidebarToggle" style="color:#64748b">
                <i class="bi bi-list fs-5"></i>
            </button>
            <span class="page-title">{{ $title }}</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="topbar-meta d-none d-md-block">
                <i class="bi bi-calendar3 me-1"></i>
                {{ now()->format('D, M d Y') }}
            </span>
            {{-- Notifications bell --}}
            @php
                $lowStockCount = \App\Models\Inventory::join('products',
                    'inventories.product_id','=','products.id')
                    ->whereRaw('inventories.quantity <= products.reorder_level')
                    ->count();
            @endphp
            @if($lowStockCount > 0)
            <a href="{{ route('inventory.index') }}?stock_status=low"
               class="btn btn-sm position-relative"
               style="color:#f59e0b;background:#fef3c7;
                      border:none;border-radius:8px;padding:6px 10px">
                <i class="bi bi-bell-fill"></i>
                <span class="position-absolute top-0 start-100
                             translate-middle badge rounded-pill bg-danger"
                      style="font-size:9px">
                    {{ $lowStockCount }}
                </span>
            </a>
            @endif
        </div>
    </header>

    {{-- Content --}}
    <main class="content-area">

        {{-- Flash messages --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show
                    d-flex align-items-center gap-2 mb-4"
             role="alert" style="border-radius:10px">
            <i class="bi bi-check-circle-fill text-success"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto"
                    data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show
                    d-flex align-items-center gap-2 mb-4"
             role="alert" style="border-radius:10px">
            <i class="bi bi-exclamation-circle-fill text-danger"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close ms-auto"
                    data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{ $slot }}
    </main>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
</script>

<script>
// Mobile sidebar toggle
const sidebar  = document.getElementById('sidebar');
const overlay  = document.getElementById('sidebarOverlay');
const toggle   = document.getElementById('sidebarToggle');

toggle?.addEventListener('click', () => {
    sidebar.classList.toggle('open');
    overlay.classList.toggle('show');
});

overlay.addEventListener('click', () => {
    sidebar.classList.remove('open');
    overlay.classList.remove('show');
});

// Auto-dismiss alerts after 4 seconds
document.querySelectorAll('.alert').forEach(alert => {
    setTimeout(() => {
        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
        bsAlert?.close();
    }, 4000);
});
</script>

</body>
</html>