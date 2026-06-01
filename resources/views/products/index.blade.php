<x-layouts.app title="Products">

    {{-- Page header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">Products</h5>
            <small class="text-muted">Manage your product catalog</small>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-dark">
            <i class="bi bi-plus-lg me-1"></i>Add Product
        </a>
    </div>

    {{-- Search & Filter --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('products.index') }}" class="row g-2">
                <div class="col-md-4">
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search name or SKU..."
                        value="{{ request('search') }}"
                    >
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}"
                                {{ request('category') == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="supplier_id" class="form-select">
                        <option value="">All Suppliers</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}"
                                {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-dark flex-grow-1">
                        <i class="bi bi-search"></i>
                    </button>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Products Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>SKU</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Price</th>
                            <th class="text-center">Stock</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>
                                <code class="text-dark">{{ $product->sku }}</code>
                            </td>
                            <td>
                                <div class="fw-medium">{{ $product->name }}</div>
                                @if($product->description)
                                    <small class="text-muted">
                                        {{ Str::limit($product->description, 40) }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10
                                             text-secondary border border-secondary-subtle">
                                    {{ $product->category }}
                                </span>
                            </td>
                            <td>{{ $product->supplier->name ?? '—' }}</td>
                            <td>₱{{ number_format($product->price, 2) }}</td>
                            <td class="text-center">
                                <span class="fw-semibold">
                                    {{ $product->inventory->quantity ?? 0 }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($product->isLowStock())
                                    <span class="badge bg-danger">Low Stock</span>
                                @else
                                    <span class="badge bg-success">In Stock</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('products.show', $product) }}"
                                       class="btn btn-sm btn-outline-secondary"
                                       title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST"
                                          action="{{ route('products.destroy', $product) }}"
                                          onsubmit="return confirm('Delete {{ $product->name }}? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-box fs-1 d-block mb-2 opacity-25"></i>
                                No products found.
                                <a href="{{ route('products.create') }}">Add your first product</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($products->hasPages())
        <div class="card-footer bg-transparent d-flex justify-content-between
                    align-items-center">
            <small class="text-muted">
                Showing {{ $products->firstItem() }}–{{ $products->lastItem() }}
                of {{ $products->total() }} products
            </small>
            {{ $products->withQueryString()->links() }}
        </div>
        @endif
    </div>

</x-layouts.app>