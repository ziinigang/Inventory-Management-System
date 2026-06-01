<x-layouts.app title="Inventory">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">Inventory</h5>
            <small class="text-muted">Real-time stock levels for all products</small>
        </div>
        <a href="{{ route('stock-movements.create') }}" class="btn btn-dark">
            <i class="bi bi-plus-lg me-1"></i>Record Movement
        </a>
    </div>

    {{-- Stat cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fw-bold fs-3 text-dark">{{ $stats['total'] }}</div>
                <div class="text-muted small">Total Items</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fw-bold fs-3 text-success">{{ $stats['healthy'] }}</div>
                <div class="text-muted small">Healthy Stock</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fw-bold fs-3 text-warning">{{ $stats['low'] }}</div>
                <div class="text-muted small">Low Stock</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fw-bold fs-3 text-danger">{{ $stats['out'] }}</div>
                <div class="text-muted small">Out of Stock</div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('inventory.index') }}"
                  class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control"
                        placeholder="Search product name or SKU..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="stock_status" class="form-select">
                        <option value="">All Stock Levels</option>
                        <option value="ok"
                            {{ request('stock_status') === 'ok' ? 'selected' : '' }}>
                            Healthy Stock
                        </option>
                        <option value="low"
                            {{ request('stock_status') === 'low' ? 'selected' : '' }}>
                            Low Stock
                        </option>
                        <option value="out"
                            {{ request('stock_status') === 'out' ? 'selected' : '' }}>
                            Out of Stock
                        </option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-dark flex-grow-1">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('inventory.index') }}"
                       class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Inventory Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Supplier</th>
                            <th>Location</th>
                            <th class="text-center">Reorder Level</th>
                            <th class="text-center">Qty in Stock</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventories as $inv)
                        @php
                            $qty      = $inv->quantity;
                            $reorder  = $inv->product->reorder_level;
                            $isOut    = $qty === 0;
                            $isLow    = !$isOut && $qty <= $reorder;
                        @endphp
                        <tr class="{{ $isOut ? 'table-danger' : ($isLow ? 'table-warning' : '') }}">
                            <td>
                                <div class="fw-medium">{{ $inv->product->name }}</div>
                                <small class="text-muted">
                                    {{ $inv->product->category }}
                                </small>
                            </td>
                            <td>
                                <code class="small">{{ $inv->product->sku }}</code>
                            </td>
                            <td>
                                <small>
                                    {{ $inv->product->supplier->name ?? '—' }}
                                </small>
                            </td>
                            <td>
                                <small>{{ $inv->location ?? '—' }}</small>
                            </td>
                            <td class="text-center">
                                <small>{{ $reorder }}</small>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold fs-5
                                    {{ $isOut ? 'text-danger' :
                                       ($isLow ? 'text-warning' : 'text-success') }}">
                                    {{ $qty }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($isOut)
                                    <span class="badge bg-danger">Out of Stock</span>
                                @elseif($isLow)
                                    <span class="badge bg-warning text-dark">
                                        Low Stock
                                    </span>
                                @else
                                    <span class="badge bg-success">In Stock</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('stock-movements.create',
                                               ['product_id' => $inv->product_id]) }}"
                                       class="btn btn-sm btn-dark"
                                       title="Record movement">
                                        <i class="bi bi-arrow-left-right"></i>
                                    </a>
                                    <a href="{{ route('inventory.show', $inv) }}"
                                       class="btn btn-sm btn-outline-secondary"
                                       title="View history">
                                        <i class="bi bi-clock-history"></i>
                                    </a>
                                    <a href="{{ route('inventory.edit', $inv) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Edit location">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-clipboard-data fs-1 d-block
                                          mb-2 opacity-25"></i>
                                No inventory records found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($inventories->hasPages())
        <div class="card-footer bg-transparent d-flex
                    justify-content-between align-items-center">
            <small class="text-muted">
                Showing {{ $inventories->firstItem() }}–{{ $inventories->lastItem() }}
                of {{ $inventories->total() }} items
            </small>
            {{ $inventories->withQueryString()->links() }}
        </div>
        @endif
    </div>

</x-layouts.app>