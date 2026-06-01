<x-layouts.app title="Supplier Detail">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">{{ $supplier->name }}</h5>
            <small class="text-muted">
                Supplier Profile &amp; Supply History
            </small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('suppliers.edit', $supplier) }}"
               class="btn btn-dark">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
            <a href="{{ route('suppliers.index') }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- Left: Supplier details --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent fw-semibold">
                    Contact Information
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <div class="text-muted small">Contact Person</div>
                            <div class="fw-medium">
                                <i class="bi bi-person me-1 text-muted"></i>
                                {{ $supplier->contact_person }}
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="text-muted small">Email</div>
                            <div class="fw-medium">
                                <i class="bi bi-envelope me-1 text-muted"></i>
                                <a href="mailto:{{ $supplier->email }}"
                                   class="text-dark text-decoration-none">
                                    {{ $supplier->email }}
                                </a>
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="text-muted small">Phone</div>
                            <div class="fw-medium">
                                <i class="bi bi-telephone me-1 text-muted"></i>
                                {{ $supplier->phone }}
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="text-muted small">Address</div>
                            <div class="fw-medium">
                                <i class="bi bi-geo-alt me-1 text-muted"></i>
                                {{ $supplier->address }}
                            </div>
                        </li>
                        <li>
                            <div class="text-muted small">Status</div>
                            <span class="badge mt-1
                                {{ $supplier->status === 'active'
                                   ? 'bg-success' : 'bg-secondary' }}
                                fs-6 px-3 py-2">
                                {{ ucfirst($supplier->status) }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Summary stats --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent fw-semibold">
                    Supply Summary
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="fw-bold fs-3 text-primary">
                                {{ $totalProducts }}
                            </div>
                            <div class="text-muted small">
                                Total Products
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="fw-bold fs-3 text-success">
                                {{ number_format($totalStock) }}
                            </div>
                            <div class="text-muted small">
                                Units in Stock
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">Supplier since</div>
                    <div class="fw-medium mb-3">
                        {{ $supplier->created_at->format('F d, Y') }}
                    </div>
                    <div class="text-muted small mb-1">Last updated</div>
                    <div class="fw-medium">
                        {{ $supplier->updated_at->format('F d, Y') }}
                    </div>
                </div>
            </div>

        </div>

        {{-- Right: Products supplied --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent d-flex
                            justify-content-between align-items-center">
                    <span class="fw-semibold">Products Supplied</span>
                    <a href="{{ route('products.create') }}"
                       class="btn btn-sm btn-outline-dark">
                        <i class="bi bi-plus me-1"></i>Add Product
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>SKU</th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-center">Stock</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr>
                                    <td>
                                        <code class="text-dark small">
                                            {{ $product->sku }}
                                        </code>
                                    </td>
                                    <td>
                                        <div class="fw-medium">
                                            {{ $product->name }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary
                                                     bg-opacity-10 text-secondary
                                                     border border-secondary-subtle">
                                            {{ $product->category }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        ₱{{ number_format($product->price, 2) }}
                                    </td>
                                    <td class="text-center fw-semibold">
                                        {{ $product->inventory->quantity ?? 0 }}
                                    </td>
                                    <td class="text-center">
                                        @if($product->isLowStock())
                                            <span class="badge bg-danger">
                                                Low Stock
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                In Stock
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('products.show', $product) }}"
                                           class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7"
                                        class="text-center py-5 text-muted">
                                        <i class="bi bi-box fs-1 d-block
                                                  mb-2 opacity-25"></i>
                                        No products from this supplier yet.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($products->hasPages())
                <div class="card-footer bg-transparent d-flex
                            justify-content-between align-items-center">
                    <small class="text-muted">
                        Showing {{ $products->firstItem() }}–{{ $products->lastItem() }}
                        of {{ $products->total() }} products
                    </small>
                    {{ $products->links() }}
                </div>
                @endif

            </div>
        </div>

    </div>

</x-layouts.app>