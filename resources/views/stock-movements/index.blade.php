<x-layouts.app title="Stock Movements">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">Stock Movements</h5>
            <small class="text-muted">Complete audit trail of all stock changes</small>
        </div>
        <a href="{{ route('stock-movements.create') }}" class="btn btn-dark">
            <i class="bi bi-plus-lg me-1"></i>Record Movement
        </a>
    </div>

    {{-- Summary stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fw-bold fs-3 text-success">
                    +{{ number_format($stats['total_in']) }}
                </div>
                <div class="text-muted small">Total Units Received</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fw-bold fs-3 text-danger">
                    −{{ number_format($stats['total_out']) }}
                </div>
                <div class="text-muted small">Total Units Released</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fw-bold fs-3 text-primary">
                    {{ $stats['today'] }}
                </div>
                <div class="text-muted small">Movements Today</div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('stock-movements.index') }}"
                  class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control"
                        placeholder="Product name or SKU..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="in"
                            {{ request('type') === 'in' ? 'selected' : '' }}>
                            Stock In
                        </option>
                        <option value="out"
                            {{ request('type') === 'out' ? 'selected' : '' }}>
                            Stock Out
                        </option>
                        <option value="adjustment"
                            {{ request('type') === 'adjustment' ? 'selected' : '' }}>
                            Adjustment
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control"
                        value="{{ request('date_from') }}"
                        placeholder="From">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control"
                        value="{{ request('date_to') }}"
                        placeholder="To">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-dark flex-grow-1">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('stock-movements.index') }}"
                       class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Movements Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date & Time</th>
                            <th>Product</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Quantity</th>
                            <th>Reason</th>
                            <th>Recorded By</th>
                            <th class="text-center">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
                        <tr>
                            <td>
                                <div class="small fw-medium">
                                    {{ $movement->created_at->format('M d, Y') }}
                                </div>
                                <div class="text-muted" style="font-size:11px">
                                    {{ $movement->created_at->format('h:i A') }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-medium">
                                    {{ $movement->product->name }}
                                </div>
                                <code class="small text-muted">
                                    {{ $movement->product->sku }}
                                </code>
                            </td>
                            <td class="text-center">
                                @if($movement->type === 'in')
                                    <span class="badge bg-success">Stock In</span>
                                @elseif($movement->type === 'out')
                                    <span class="badge bg-danger">Stock Out</span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        Adjustment
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                             @if($movement->type === 'out')
                                <span class="fw-bold text-danger">− {{ abs($movement->quantity) }}</span>
                            @elseif($movement->type === 'in')
                                 <span class="fw-bold text-success">+ {{ abs($movement->quantity) }}</span>
                            @else
                              <span class="fw-bold {{ $movement->quantity < 0 ? 'text-danger' : 'text-success' }}">
                           {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                              </span>
                            @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ Str::limit($movement->reason ?? '—', 40) }}
                                </small>
                            </td>
                            <td>
                                <small>{{ $movement->user->name ?? '—' }}</small>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('stock-movements.show',
                                           $movement) }}"
                                   class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-arrow-left-right fs-1 d-block
                                          mb-2 opacity-25"></i>
                                No movements recorded yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($movements->hasPages())
        <div class="card-footer bg-transparent d-flex
                    justify-content-between align-items-center">
            <small class="text-muted">
                Showing {{ $movements->firstItem() }}–{{ $movements->lastItem() }}
                of {{ $movements->total() }} records
            </small>
            {{ $movements->withQueryString()->links() }}
        </div>
        @endif
    </div>

</x-layouts.app>