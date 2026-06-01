<x-layouts.app title="Edit Product">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">Edit Product</h5>
            <small class="text-muted">{{ $product->sku }} — {{ $product->name }}</small>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>

    <form method="POST" action="{{ route('products.update', $product) }}">
        @csrf
        @method('PUT')

        <div class="row g-4">

            <div class="col-md-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent fw-semibold">
                        Product Information
                    </div>
                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-12">
                                <label class="form-label fw-medium">
                                    Product Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $product->name) }}"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">SKU</label>
                                <input type="text" name="sku"
                                    class="form-control @error('sku') is-invalid @enderror"
                                    value="{{ old('sku', $product->sku) }}"
                                    required>
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Category</label>
                                <input type="text" name="category"
                                    class="form-control @error('category') is-invalid @enderror"
                                    value="{{ old('category', $product->category) }}"
                                    list="category-list"
                                    required>
                                <datalist id="category-list">
                                    <option value="Office Supplies">
                                    <option value="Office Equipment">
                                    <option value="Classroom Supplies">
                                    <option value="Cleaning Supplies">
                                    <option value="Electronics">
                                </datalist>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-medium">Description</label>
                                <textarea name="description" rows="3"
                                    class="form-control">{{ old('description', $product->description) }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Storage Location</label>
                                <input type="text" name="location"
                                    class="form-control"
                                    value="{{ old('location', $product->inventory->location ?? '') }}"
                                    placeholder="e.g. Shelf A-1">
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent fw-semibold">
                        Pricing & Supplier
                    </div>
                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-12">
                                <label class="form-label fw-medium">Supplier</label>
                                <select name="supplier_id" class="form-select" required>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                            {{ old('supplier_id', $product->supplier_id)
                                               == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-medium">Price (₱)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" name="price"
                                        step="0.01" min="0"
                                        class="form-control"
                                        value="{{ old('price', $product->price) }}"
                                        required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-medium">Reorder Level</label>
                                <input type="number" name="reorder_level" min="0"
                                    class="form-control"
                                    value="{{ old('reorder_level', $product->reorder_level) }}"
                                    required>
                                <div class="form-text">
                                    Current stock:
                                    <strong>{{ $product->inventory->quantity ?? 0 }}</strong>
                                    units
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-dark py-2">
                        <i class="bi bi-check-lg me-1"></i>Update Product
                    </button>
                    <a href="{{ route('products.show', $product) }}"
                       class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </div>

        </div>
    </form>

</x-layouts.app>