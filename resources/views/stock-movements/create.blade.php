<x-layouts.app title="Record Stock Movement">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">Record Stock Movement</h5>
            <small class="text-muted">
                Update stock levels — all changes are permanently recorded
            </small>
        </div>
        <a href="{{ route('stock-movements.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent fw-semibold">
                    Movement Details
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('stock-movements.store') }}"
                          id="movementForm">
                        @csrf

                        {{-- Product selector --}}
                        <div class="mb-3">
                            <label class="form-label fw-medium">
                                Product <span class="text-danger">*</span>
                            </label>
                            <select name="product_id" id="productSelect"
                                class="form-select
                                       @error('product_id') is-invalid @enderror"
                                required>
                                <option value="">— Select a product —</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}"
                                        data-stock="{{ $product->inventory->quantity ?? 0 }}"
                                        data-reorder="{{ $product->reorder_level }}"
                                        {{ (old('product_id',
                                            $selectedProduct?->id) == $product->id)
                                           ? 'selected' : '' }}>
                                        {{ $product->name }}
                                        ({{ $product->sku }}) —
                                        {{ $product->inventory->quantity ?? 0 }} in stock
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Live stock display --}}
                        <div id="stockInfo" class="alert alert-secondary mb-3 d-none">
                            <div class="d-flex justify-content-between">
                                <span>
                                    <i class="bi bi-box me-1"></i>
                                    Current Stock:
                                    <strong id="currentStock">0</strong> units
                                </span>
                                <span>
                                    Reorder Level:
                                    <strong id="reorderLevel">0</strong>
                                </span>
                            </div>
                        </div>

                        {{-- Movement type --}}
                        <div class="mb-3">
                            <label class="form-label fw-medium">
                                Movement Type <span class="text-danger">*</span>
                            </label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <input type="radio" class="btn-check"
                                           name="type" id="typeIn"
                                           value="in"
                                           {{ old('type', 'in') === 'in'
                                              ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success w-100"
                                           for="typeIn">
                                        <i class="bi bi-arrow-down-circle me-1"></i>
                                        Stock In
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check"
                                           name="type" id="typeOut"
                                           value="out"
                                           {{ old('type') === 'out'
                                              ? 'checked' : '' }}>
                                    <label class="btn btn-outline-danger w-100"
                                           for="typeOut">
                                        <i class="bi bi-arrow-up-circle me-1"></i>
                                        Stock Out
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check"
                                           name="type" id="typeAdj"
                                           value="adjustment"
                                           {{ old('type') === 'adjustment'
                                              ? 'checked' : '' }}>
                                    <label class="btn btn-outline-warning w-100"
                                           for="typeAdj">
                                        <i class="bi bi-sliders me-1"></i>
                                        Adjustment
                                    </label>
                                </div>
                            </div>
                            @error('type')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Quantity --}}
                        <div class="mb-3">
                            <label class="form-label fw-medium">
                                Quantity <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="quantity" id="qtyInput"
                                class="form-control @error('quantity') is-invalid @enderror"
                                value="{{ old('quantity', 1) }}"
                                min="-9999"
                                required>
                            <div class="form-text" id="qtyHint">
                                Enter the number of units to add or remove.
                                For adjustments, use a negative number to reduce stock.
                            </div>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Projected result --}}
                        <div id="projectedResult"
                             class="alert alert-light border mb-3 d-none">
                            <small>
                                Projected stock after this movement:
                                <strong id="projectedQty" class="fs-6">—</strong>
                                units
                            </small>
                        </div>

                        {{-- Reason --}}
                        <div class="mb-4">
                            <label class="form-label fw-medium">
                                Reason / Notes
                            </label>
                            <input type="text" name="reason"
                                class="form-control"
                                value="{{ old('reason') }}"
                                placeholder="e.g. Restocking from supplier, Issued to Dept A">
                        </div>

                        <div class="d-grid">
                            <button type="submit"
                                    class="btn btn-dark py-2 fw-medium">
                                <i class="bi bi-check-lg me-1"></i>
                                Record Movement
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Live stock preview script --}}
    <script>
    const productSelect = document.getElementById('productSelect');
    const stockInfo     = document.getElementById('stockInfo');
    const currentStock  = document.getElementById('currentStock');
    const reorderLevel  = document.getElementById('reorderLevel');
    const qtyInput      = document.getElementById('qtyInput');
    const projected     = document.getElementById('projectedResult');
    const projectedQty  = document.getElementById('projectedQty');

    // Update min attribute based on movement type
    function updateMin() {
        const type = document.querySelector('input[name="type"]:checked')?.value;
        if (type === 'adjustment') {
            qtyInput.min = '-9999';
            qtyInput.placeholder = 'Use negative (e.g. -10) to reduce stock';
        } else {
            qtyInput.min = '1';
            qtyInput.placeholder = '';
        }
    }

    function updatePreview() {
        const option  = productSelect.options[productSelect.selectedIndex];
        const stock   = parseInt(option.dataset.stock ?? 0);
        const reorder = parseInt(option.dataset.reorder ?? 0);
        const qty     = parseInt(qtyInput.value) || 0;
        const type    = document.querySelector('input[name="type"]:checked')?.value;

        if (productSelect.value) {
            currentStock.textContent = stock;
            reorderLevel.textContent = reorder;
            stockInfo.classList.remove('d-none');

            let newQty = stock;
            if (type === 'in')                  newQty = stock + Math.abs(qty);
            else if (type === 'out')            newQty = stock - Math.abs(qty);
            else if (type === 'adjustment')     newQty = stock + qty;

            projectedQty.textContent = newQty;
            projectedQty.className   = newQty < 0 ? 'fs-6 text-danger fw-bold'
                                                   : 'fs-6 text-success fw-bold';
            projected.classList.remove('d-none');
        } else {
            stockInfo.classList.add('d-none');
            projected.classList.add('d-none');
        }
    }

    productSelect.addEventListener('change', updatePreview);
    qtyInput.addEventListener('input', updatePreview);
    document.querySelectorAll('input[name="type"]').forEach(r => {
        r.addEventListener('change', () => {
            updateMin();
            updatePreview();
        });
    });

    // Trigger on page load
    updateMin();
    if (productSelect.value) updatePreview();
    </script>

</x-layouts.app>