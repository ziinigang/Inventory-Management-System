<x-layouts.app title="Edit Storage Location">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">Edit Storage Location</h5>
            <small class="text-muted">{{ $inventory->product->name }}</small>
        </div>
        <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent fw-semibold">
                    Storage Location
                </div>
                <div class="card-body">

                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        To change stock quantity, use
                        <a href="{{ route('stock-movements.create',
                                   ['product_id' => $inventory->product_id]) }}"
                           class="alert-link">Record Movement</a> instead.
                    </div>

                    <form method="POST"
                          action="{{ route('inventory.update', $inventory) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-medium">
                                Current Quantity
                            </label>
                            <input type="text"
                                   class="form-control bg-light"
                                   value="{{ $inventory->quantity }} units"
                                   disabled>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium">
                                Storage Location
                            </label>
                            <input type="text" name="location"
                                class="form-control
                                       @error('location') is-invalid @enderror"
                                value="{{ old('location', $inventory->location) }}"
                                placeholder="e.g. Shelf A-1, Warehouse B">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark px-4">
                                <i class="bi bi-check-lg me-1"></i>Update Location
                            </button>
                            <a href="{{ route('inventory.index') }}"
                               class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>