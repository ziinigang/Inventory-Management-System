<x-layouts.app title="Movement Detail">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">Movement Detail</h5>
            <small class="text-muted">Reference #{{ $stockMovement->id }}</small>
        </div>
        <a href="{{ route('stock-movements.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    <div class="text-center mb-4">
                        @if($stockMovement->type === 'in')
                            <div class="display-6 text-success fw-bold">
                                +{{ $stockMovement->quantity }}
                            </div>
                            <span class="badge bg-success fs-6 px-3 py-2">
                                Stock In
                            </span>
                        @elseif($stockMovement->type === 'out')
                            <div class="display-6 text-danger fw-bold">
                                −{{ abs($stockMovement->quantity) }}
                            </div>
                            <span class="badge bg-danger fs-6 px-3 py-2">
                                Stock Out
                            </span>
                        @else
                            <div class="display-6 text-warning fw-bold">
                                {{ $stockMovement->quantity > 0 ? '+' : '' }}
                                {{ $stockMovement->quantity }}
                            </div>
                            <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                Adjustment
                            </span>
                        @endif
                        <div class="text-muted mt-2">units</div>
                    </div>

                    <hr>

                    <dl class="row mb-0">
                        <dt class="col-5 text-muted fw-normal">Product</dt>
                        <dd class="col-7">
                            <a href="{{ route('products.show',
                                       $stockMovement->product) }}"
                               class="text-dark fw-medium">
                                {{ $stockMovement->product->name }}
                            </a>
                            <div class="text-muted small">
                                {{ $stockMovement->product->sku }}
                            </div>
                        </dd>

                        <dt class="col-5 text-muted fw-normal">Current Stock</dt>
                        <dd class="col-7 fw-semibold">
                            {{ $stockMovement->product->inventory->quantity ?? '—' }}
                            units
                        </dd>

                        <dt class="col-5 text-muted fw-normal">Reason</dt>
                        <dd class="col-7">
                            {{ $stockMovement->reason ?? '—' }}
                        </dd>

                        <dt class="col-5 text-muted fw-normal">Recorded By</dt>
                        <dd class="col-7">
                            {{ $stockMovement->user->name ?? '—' }}
                        </dd>

                        <dt class="col-5 text-muted fw-normal">Date & Time</dt>
                        <dd class="col-7">
                            {{ $stockMovement->created_at->format('F d, Y h:i A') }}
                        </dd>

                        <dt class="col-5 text-muted fw-normal">Reference #</dt>
                        <dd class="col-7 text-muted">
                            #{{ $stockMovement->id }}
                        </dd>
                    </dl>

                </div>
            </div>
        </div>
    </div>

</x-layouts.app>