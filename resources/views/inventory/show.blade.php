<x-layouts.app title="Stock History">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">
                {{ $inventory->product->name }}
            </h5>
            <small class="text-muted">
                <code>{{ $inventory->product->sku }}</code>
                &middot; Stock History
            </small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('stock-movements.create',
                       ['product_id' => $inventory->product_id]) }}"
               class="btn btn-dark">
                <i class="bi bi-plus-lg me-1"></i>Record Movement
            </a>
            <a href="{{ route('inventory.index') }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- Stock summary --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center py-4">
                    <div class="display-4 fw-bold
                        {{ $inventory->product->isLowStock()
                           ? 'text-danger' : 'text-success' }}">
                        {{ $inventory->quantity }}
                    </div>
                    <div class="text-muted">units in stock</div>
                    <hr>
                    @if($inventory->quantity === 0)
                        <span class="badge bg-danger fs-6 px-3 py-2">
                            <i class="bi bi-x-circle me-1"></i>Out of Stock
                        </span>
                    @elseif($inventory->product->isLowStock())
                        <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                            <i class="bi bi-exclamation-triangle me-1"></i>Low Stock
                        </span>
                    @else
                        <span class="badge bg-success fs-6 px-3 py-2">
                            <i class="bi bi-check-circle me-1"></i>In Stock
                        </span>
                    @endif
                    <div class="mt-3 text-muted small">
                        Reorder at: {{ $inventory->product->reorder_level }} units
                    </div>
                    <div class="text-muted small mt-1">
                        Location: {{ $inventory->location ?? 'Not set' }}
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent fw-semibold">
                    Product Info
                </div>
                <div class="card-body">
                    <div class="text-muted small">Supplier</div>
                    <div class="fw-medium mb-2">
                        {{ $inventory->product->supplier->name ?? '—' }}
                    </div>
                    <div class="text-muted small">Category</div>
                    <div class="fw-medium mb-2">
                        {{ $inventory->product->category }}
                    </div>
                    <div class="text-muted small">Unit Price</div>
                    <div class="fw-medium">
                        ₱{{ number_format($inventory->product->price, 2) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Movements history --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent fw-semibold">
                    All Stock Movements
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date & Time</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">Quantity</th>
                                <th>Reason</th>
                                <th>Recorded By</th>
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
                                <td class="text-center">
                                    @if($movement->type === 'in')
                                        <span class="badge bg-success">
                                            <i class="bi bi-arrow-down-circle me-1"></i>
                                            Stock In
                                        </span>
                                    @elseif($movement->type === 'out')
                                        <span class="badge bg-danger">
                                            <i class="bi bi-arrow-up-circle me-1"></i>
                                            Stock Out
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-sliders me-1"></i>
                                            Adjustment
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold
                                        {{ $movement->type === 'out'
                                           ? 'text-danger' : 'text-success' }}">
                                        {{ $movement->type === 'out' ? '−' : '+' }}
                                        {{ abs($movement->quantity) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $movement->reason ?? '—' }}
                                    </small>
                                </td>
                                <td>
                                    <small>{{ $movement->user->name ?? '—' }}</small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    No movements recorded yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($movements->hasPages())
                <div class="card-footer bg-transparent">
                    {{ $movements->links() }}
                </div>
                @endif
            </div>
        </div>

    </div>

</x-layouts.app>