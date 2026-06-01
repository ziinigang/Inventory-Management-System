@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 bg-primary bg-opacity-10">
                        <i class="bi bi-box fs-4 text-primary"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Products</div>
                        <div class="fw-bold fs-4">{{ $totalProducts }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 bg-success bg-opacity-10">
                        <i class="bi bi-truck fs-4 text-success"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Suppliers</div>
                        <div class="fw-bold fs-4">{{ $totalSuppliers }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 bg-warning bg-opacity-10">
                        <i class="bi bi-exclamation-triangle fs-4 text-warning"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Low Stock Items</div>
                        <div class="fw-bold fs-4">{{ $lowStockCount }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 bg-info bg-opacity-10">
                        <i class="bi bi-arrow-left-right fs-4 text-info"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Stock Movements</div>
                        <div class="fw-bold fs-4">{{ $totalMovements }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Movements Table --}}
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Recent Stock Movements</span>
            <a href="{{ route('stock-movements.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">Qty</th>
                        <th>Reason</th>
                        <th>By</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentMovements as $m)
                    <tr>
                        <td class="fw-medium small">{{ $m->product->name }}</td>
                        <td class="text-center">
                            @if($m->type === 'in')
                                <span class="badge bg-success">In</span>
                            @elseif($m->type === 'out')
                                <span class="badge bg-danger">Out</span>
                            @else
                                <span class="badge bg-warning text-dark">Adj</span>
                            @endif
                        </td>
                        <td class="text-center fw-semibold {{ $m->type === 'out' ? 'text-danger' : 'text-success' }}">
                            {{ $m->type === 'out' ? '−' : '+' }}{{ abs($m->quantity) }}
                        </td>
                        <td><small class="text-muted">{{ Str::limit($m->reason ?? '—', 30) }}</small></td>
                        <td><small>{{ $m->user->name ?? '—' }}</small></td>
                        <td><small class="text-muted">{{ $m->created_at->diffForHumans() }}</small></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">
                            No movements yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h6 class="fw-semibold mb-1">Welcome, {{ auth()->user()->name }}</h6>
            <p class="text-muted mb-0 small">
                You are logged in as <strong>{{ auth()->user()->role }}</strong>.
                Use the sidebar to navigate.
            </p>
        </div>
    </div>

@endsection