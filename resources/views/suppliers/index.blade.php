<x-layouts.app title="Suppliers">

    {{-- Page header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">Suppliers</h5>
            <small class="text-muted">Manage your supplier directory</small>
        </div>
        <a href="{{ route('suppliers.create') }}" class="btn btn-dark">
            <i class="bi bi-plus-lg me-1"></i>Add Supplier
        </a>
    </div>

    {{-- Search & Filter --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('suppliers.index') }}"
                  class="row g-2 align-items-center">
                <div class="col-md-6">
                    <input type="text" name="search"
                        class="form-control"
                        placeholder="Search by name, email or contact person..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active"
                            {{ request('status') === 'active' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="inactive"
                            {{ request('status') === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-dark flex-grow-1">
                        <i class="bi bi-search me-1"></i>Search
                    </button>
                    <a href="{{ route('suppliers.index') }}"
                       class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Suppliers Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Supplier Name</th>
                            <th>Contact Person</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th class="text-center">Products</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                        <tr>
                            <td class="text-muted small">
                                {{ $suppliers->firstItem() + $loop->index }}
                            </td>
                            <td>
                                <div class="fw-medium">{{ $supplier->name }}</div>
                                <small class="text-muted">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    {{ Str::limit($supplier->address, 35) }}
                                </small>
                            </td>
                            <td>{{ $supplier->contact_person }}</td>
                            <td>
                                <a href="mailto:{{ $supplier->email }}"
                                   class="text-decoration-none text-dark">
                                    {{ $supplier->email }}
                                </a>
                            </td>
                            <td>{{ $supplier->phone }}</td>
                            <td class="text-center">
                                <a href="{{ route('suppliers.show', $supplier) }}"
                                   class="badge bg-primary bg-opacity-10
                                          text-primary text-decoration-none border
                                          border-primary-subtle px-3 py-2">
                                    {{ $supplier->products_count }}
                                    {{ Str::plural('product', $supplier->products_count) }}
                                </a>
                            </td>
                            <td class="text-center">
                                <form method="POST"
                                      action="{{ route('suppliers.toggle-status', $supplier) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="badge border-0
                                               {{ $supplier->status === 'active'
                                                  ? 'bg-success' : 'bg-secondary' }}
                                               fs-6 px-3 py-2"
                                        title="Click to toggle status">
                                        {{ ucfirst($supplier->status) }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('suppliers.show', $supplier) }}"
                                       class="btn btn-sm btn-outline-secondary"
                                       title="View supply history">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('suppliers.edit', $supplier) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST"
                                          action="{{ route('suppliers.destroy', $supplier) }}"
                                          onsubmit="return confirm(
                                              'Delete {{ $supplier->name }}?\n\nThis will fail if they have linked products.')">
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
                                <i class="bi bi-truck fs-1 d-block mb-2 opacity-25"></i>
                                No suppliers found.
                                <a href="{{ route('suppliers.create') }}">
                                    Add your first supplier
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($suppliers->hasPages())
        <div class="card-footer bg-transparent d-flex justify-content-between
                    align-items-center">
            <small class="text-muted">
                Showing {{ $suppliers->firstItem() }}–{{ $suppliers->lastItem() }}
                of {{ $suppliers->total() }} suppliers
            </small>
            {{ $suppliers->withQueryString()->links() }}
        </div>
        @endif
    </div>

</x-layouts.app>