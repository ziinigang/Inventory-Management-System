<x-layouts.app title="Dashboard">

@php
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
@endphp

{{-- Low stock banner --}}
@if($lowStockCount > 0)
<div class="low-stock-banner">
    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
    <div>
        <strong>{{ $lowStockCount }} item(s) are running low on stock.</strong>
        <a href="{{ route('inventory.index') }}?stock_status=low"
           class="ms-2 fw-semibold" style="color:#92400e">
            View items →
        </a>
    </div>
</div>
@endif

{{-- Greeting --}}
<div class="mb-4">
    <h4 class="fw-bold mb-0">
        {{ $greeting }}, {{ explode(' ', auth()->user()->name)[0] }} 👋
    </h4>
    <p class="text-muted mb-0" style="font-size:13px">
        Here's what's happening in your inventory today.
    </p>
</div>

{{-- Stat cards --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-md-3">
        <a href="{{ route('products.index') }}" class="text-decoration-none">
            <div class="stat-card">
                <div class="stat-icon"
                     style="background:#dbeafe">
                    <i class="bi bi-box" style="color:#1d4ed8"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $totalProducts }}</div>
                    <div class="stat-label">Total Products</div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-6 col-md-3">
        <a href="{{ route('suppliers.index') }}" class="text-decoration-none">
            <div class="stat-card">
                <div class="stat-icon"
                     style="background:#dcfce7">
                    <i class="bi bi-truck" style="color:#15803d"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $totalSuppliers }}</div>
                    <div class="stat-label">Active Suppliers</div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-6 col-md-3">
        <a href="{{ route('inventory.index') }}?stock_status=low"
           class="text-decoration-none">
            <div class="stat-card">
                <div class="stat-icon"
                     style="background:#fef3c7">
                    <i class="bi bi-exclamation-triangle"
                       style="color:#d97706"></i>
                </div>
                <div>
                    <div class="stat-value"
                         style="color:{{ $lowStockCount > 0 ? '#d97706' : '#0f172a' }}">
                        {{ $lowStockCount }}
                    </div>
                    <div class="stat-label">Low Stock Items</div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-6 col-md-3">
        <a href="{{ route('stock-movements.index') }}"
           class="text-decoration-none">
            <div class="stat-card">
                <div class="stat-icon"
                     style="background:#ede9fe">
                    <i class="bi bi-arrow-left-right"
                       style="color:#7c3aed"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $totalMovements }}</div>
                    <div class="stat-label">Total Movements</div>
                </div>
            </div>
        </a>
    </div>

</div>

{{-- Charts + Low Stock Table --}}
<div class="row g-4 mb-4">

    {{-- Movement trend chart --}}
    <div class="col-md-7">
        <div class="card border-0 h-100">
            <div class="card-header bg-white d-flex
                        justify-content-between align-items-center">
                <span class="fw-semibold">Stock Movements — Last 7 Days</span>
            </div>
            <div class="card-body">
                <canvas id="movementChart" height="130"></canvas>
            </div>
        </div>
    </div>

    {{-- Inventory status donut --}}
    <div class="col-md-5">
        <div class="card border-0 h-100">
            <div class="card-header bg-white">
                <span class="fw-semibold">Stock Status Overview</span>
            </div>
            <div class="card-body d-flex flex-column
                        align-items-center justify-content-center">
                <canvas id="statusChart" width="180" height="180"></canvas>
                <div class="d-flex gap-3 mt-3" style="font-size:12px">
                    <span>
                        <span style="background:#22c55e;
                                     display:inline-block;
                                     width:10px;height:10px;
                                     border-radius:2px;
                                     margin-right:4px"></span>
                        In Stock ({{ $stockStats['in_stock'] }})
                    </span>
                    <span>
                        <span style="background:#f59e0b;
                                     display:inline-block;
                                     width:10px;height:10px;
                                     border-radius:2px;
                                     margin-right:4px"></span>
                        Low ({{ $stockStats['low_stock'] }})
                    </span>
                    <span>
                        <span style="background:#ef4444;
                                     display:inline-block;
                                     width:10px;height:10px;
                                     border-radius:2px;
                                     margin-right:4px"></span>
                        Out ({{ $stockStats['out_of_stock'] }})
                    </span>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Recent movements + Low stock table --}}
<div class="row g-4">

    {{-- Recent movements --}}
    <div class="col-md-7">
        <div class="card border-0">
            <div class="card-header bg-white d-flex
                        justify-content-between align-items-center">
                <span class="fw-semibold">Recent Movements</span>
                <a href="{{ route('stock-movements.index') }}"
                   class="btn btn-sm btn-outline-secondary"
                   style="font-size:12px">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Qty</th>
                            <th>By</th>
                            <th>When</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentMovements as $m)
                        <tr>
                            <td>
                                <div class="fw-medium"
                                     style="font-size:13px">
                                    {{ Str::limit($m->product->name, 25) }}
                                </div>
                                <code style="font-size:10px;color:#94a3b8">
                                    {{ $m->product->sku }}
                                </code>
                            </td>
                            <td class="text-center">
                                @if($m->type === 'in')
                                    <span class="badge"
                                          style="background:#dcfce7;color:#15803d">
                                        In
                                    </span>
                                @elseif($m->type === 'out')
                                    <span class="badge"
                                          style="background:#fee2e2;color:#b91c1c">
                                        Out
                                    </span>
                                @else
                                    <span class="badge"
                                          style="background:#fef3c7;color:#b45309">
                                        Adj
                                    </span>
                                @endif
                            </td>
                            <td class="text-center fw-semibold"
                                style="color:{{ $m->type === 'out'
                                       ? '#ef4444' : '#22c55e' }}">
                                {{ $m->type === 'out' ? '−' : '+' }}
                                {{ abs($m->quantity) }}
                            </td>
                            <td style="font-size:12px">
                                {{ $m->user->name ?? '—' }}
                            </td>
                            <td style="font-size:11px;color:#94a3b8">
                                {{ $m->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5"
                                class="text-center py-4 text-muted">
                                No movements yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Low stock table --}}
    <div class="col-md-5">
        <div class="card border-0">
            <div class="card-header bg-white d-flex
                        justify-content-between align-items-center">
                <span class="fw-semibold">
                    <i class="bi bi-exclamation-triangle-fill
                              text-warning me-1"></i>
                    Low Stock Alerts
                </span>
                <a href="{{ route('inventory.index') }}?stock_status=low"
                   class="btn btn-sm btn-outline-secondary"
                   style="font-size:12px">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-center">Stock</th>
                            <th class="text-center">Min</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowStockItems as $inv)
                        <tr>
                            <td>
                                <div class="fw-medium"
                                     style="font-size:13px">
                                    {{ Str::limit($inv->product->name, 20) }}
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold"
                                      style="color:{{ $inv->quantity === 0
                                             ? '#ef4444' : '#f59e0b' }}">
                                    {{ $inv->quantity }}
                                </span>
                            </td>
                            <td class="text-center text-muted"
                                style="font-size:12px">
                                {{ $inv->product->reorder_level }}
                            </td>
                            <td>
                                <a href="{{ route('stock-movements.create',
                                           ['product_id' => $inv->product_id]) }}"
                                   class="btn btn-xs btn-dark"
                                   style="font-size:11px;
                                          padding:3px 8px;
                                          border-radius:6px">
                                    Restock
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4"
                                class="text-center py-4">
                                <i class="bi bi-check-circle-fill
                                          text-success me-1"></i>
                                <span class="text-muted">
                                    All items are well stocked!
                                </span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js">
</script>
<script>
// ── Movement trend bar chart ────────────────────────────────────
const movCtx = document.getElementById('movementChart').getContext('2d');
new Chart(movCtx, {
    type: 'bar',
    data: {
        labels : @json($chartData['labels']),
        datasets: [
            {
                label          : 'Stock In',
                data           : @json($chartData['in']),
                backgroundColor: '#86efac',
                borderColor    : '#22c55e',
                borderWidth    : 1,
                borderRadius   : 4,
            },
            {
                label          : 'Stock Out',
                data           : @json($chartData['out']),
                backgroundColor: '#fca5a5',
                borderColor    : '#ef4444',
                borderWidth    : 1,
                borderRadius   : 4,
            },
        ]
    },
    options: {
        responsive: true,
        plugins   : { legend: { position: 'top' } },
        scales    : {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 },
                grid : { color: '#f1f5f9' },
            },
            x: { grid: { display: false } },
        }
    }
});

// ── Stock status donut chart ────────────────────────────────────
const statCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statCtx, {
    type: 'doughnut',
    data: {
        labels  : ['In Stock', 'Low Stock', 'Out of Stock'],
        datasets: [{
            data           : [
                {{ $stockStats['in_stock'] }},
                {{ $stockStats['low_stock'] }},
                {{ $stockStats['out_of_stock'] }},
            ],
            backgroundColor: ['#22c55e', '#f59e0b', '#ef4444'],
            borderWidth    : 0,
            hoverOffset    : 4,
        }]
    },
    options: {
        responsive: false,
        cutout    : '72%',
        plugins   : { legend: { display: false } },
    }
});
</script>

</x-layouts.app>