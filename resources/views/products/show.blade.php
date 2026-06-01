<x-layouts.app title="Product Detail">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">{{ $product->name }}</h5>
            <small class="text-muted">
                <code>{{ $product->sku }}</code> &middot; {{ $product->category }}
            </small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('products.edit', $product) }}" class="btn btn-dark">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-md-8">

            {{-- Product details --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent fw-semibold">
                    Product Details
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted small">Supplier</div>
                            <div class="fw-medium">{{ $product->supplier->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Category</div>
                            <div class="fw-medium">{{ $product->category }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Unit Price</div>
                            <div class="fw-medium">₱{{ number_format($product->price, 2) }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Storage Location</div>
                            <div class="fw-medium">
                                {{ $product->inventory->location ?? '—' }}
                            </div>
                        </div>
                        @if($product->description)
                        <div class="col-12">
                            <div class="text-muted small">Description</div>
                            <div>{{ $product->description }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Recent movements --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent fw-semibold d-flex
                            justify-content-between align-items-center">
                    Recent Stock Movements
                    <a href="{{ route('stock-movements.index') }}" class="btn btn-sm btn-outline-secondary">
                        View All
                    </a>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Qty</th>
                                <th>Reason</th>
                                <th>By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMovements as $movement)
                            <tr>
                                <td>
                                    <small>{{ $movement->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    @if($movement->type === 'in')
                                        <span class="badge bg-success">Stock In</span>
                                    @elseif($movement->type === 'out')
                                        <span class="badge bg-danger">Stock Out</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Adjustment</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="{{ $movement->type === 'out' ? 'text-danger' : 'text-success' }} fw-semibold">
                                        {{ $movement->type === 'out' ? '-' : '+' }}{{ $movement->quantity }}
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
                                <td colspan="5" class="text-center text-muted py-3">
                                    No movements yet
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- Right sidebar: stock card --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center py-4">
                    <div class="display-4 fw-bold
                        {{ $product->isLowStock() ? 'text-danger' : 'text-success' }}">
                        {{ $product->inventory->quantity ?? 0 }}
                    </div>
                    <div class="text-muted">units in stock</div>
                    <hr>
                    @if($product->isLowStock())
                        <span class="badge bg-danger fs-6 px-3 py-2">
                            <i class="bi bi-exclamation-triangle me-1"></i>Low Stock
                        </span>
                    @else
                        <span class="badge bg-success fs-6 px-3 py-2">
                            <i class="bi bi-check-circle me-1"></i>In Stock
                        </span>
                    @endif
                    <div class="mt-3 text-muted small">
                        Reorder level: {{ $product->reorder_level }} units
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">Added on</div>
                    <div class="fw-medium mb-3">
                        {{ $product->created_at->format('F d, Y') }}
                    </div>
                    <div class="text-muted small mb-1">Last updated</div>
                    <div class="fw-medium">
                        {{ $product->updated_at->format('F d, Y') }}
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-layouts.app>