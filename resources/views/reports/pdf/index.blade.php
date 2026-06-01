<x-layouts.app title="Reports">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">Reports</h5>
            <small class="text-muted">
                Export system data as PDF, Excel, or CSV
            </small>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fw-bold fs-3">{{ $summary['total_products'] }}</div>
                <div class="text-muted small">Total Products</div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fw-bold fs-3 text-warning">
                    {{ $summary['low_stock_count'] }}
                </div>
                <div class="text-muted small">Low Stock Items</div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fw-bold fs-3 text-danger">
                    {{ $summary['out_of_stock'] }}
                </div>
                <div class="text-muted small">Out of Stock</div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fw-bold fs-3">{{ $summary['total_suppliers'] }}</div>
                <div class="text-muted small">Suppliers</div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fw-bold fs-3 text-primary">
                    {{ $summary['movements_today'] }}
                </div>
                <div class="text-muted small">Movements Today</div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fw-bold fs-3 text-info">
                    {{ $summary['movements_month'] }}
                </div>
                <div class="text-muted small">This Month</div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Inventory Report --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-3 p-2 bg-primary bg-opacity-10">
                            <i class="bi bi-clipboard-data text-primary fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Inventory Report</div>
                            <small class="text-muted">
                                Current stock levels for all products
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        Exports all products with their current stock quantities,
                        locations, and status (In Stock / Low Stock / Out of Stock).
                    </p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('reports.inventory.pdf') }}"
                           class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-file-earmark-pdf me-1"></i>
                            Download PDF
                        </a>
                        <a href="{{ route('reports.inventory.excel') }}"
                           class="btn btn-outline-success btn-sm">
                            <i class="bi bi-file-earmark-excel me-1"></i>
                            Download Excel (.xlsx)
                        </a>
                        <a href="{{ route('reports.inventory.csv') }}"
                           class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-file-earmark-text me-1"></i>
                            Download CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stock Movements Report --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-3 p-2 bg-success bg-opacity-10">
                            <i class="bi bi-arrow-left-right text-success fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Stock Movements Report</div>
                            <small class="text-muted">
                                Filter by type and date range
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        Full audit trail of all stock changes. Filter before
                        exporting to get exactly the records you need.
                    </p>

                    {{-- Filter form --}}
                    <div class="row g-2 mb-3">
                        <div class="col-12">
                            <select id="movType" class="form-select form-select-sm">
                                <option value="">All Types</option>
                                <option value="in">Stock In</option>
                                <option value="out">Stock Out</option>
                                <option value="adjustment">Adjustment</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <input type="date" id="movFrom"
                                   class="form-control form-control-sm"
                                   placeholder="From">
                        </div>
                        <div class="col-6">
                            <input type="date" id="movTo"
                                   class="form-control form-control-sm"
                                   placeholder="To">
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button onclick="exportMovements('pdf')"
                                class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-file-earmark-pdf me-1"></i>
                            Download PDF
                        </button>
                        <button onclick="exportMovements('excel')"
                                class="btn btn-outline-success btn-sm">
                            <i class="bi bi-file-earmark-excel me-1"></i>
                            Download Excel (.xlsx)
                        </button>
                        <button onclick="exportMovements('csv')"
                                class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-file-earmark-text me-1"></i>
                            Download CSV
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Suppliers Report --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-3 p-2 bg-warning bg-opacity-10">
                            <i class="bi bi-truck text-warning fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Suppliers Report</div>
                            <small class="text-muted">
                                Directory with product counts
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        Complete supplier directory including contact information,
                        status, and number of products supplied.
                    </p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('reports.suppliers.pdf') }}"
                           class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-file-earmark-pdf me-1"></i>
                            Download PDF
                        </a>
                        <a href="{{ route('reports.suppliers.excel') }}"
                           class="btn btn-outline-success btn-sm">
                            <i class="bi bi-file-earmark-excel me-1"></i>
                            Download Excel (.xlsx)
                        </a>
                        <a href="{{ route('reports.suppliers.csv') }}"
                           class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-file-earmark-text me-1"></i>
                            Download CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- JavaScript for filtered movements export --}}
    <script>
    function exportMovements(format) {
        const type     = document.getElementById('movType').value;
        const dateFrom = document.getElementById('movFrom').value;
        const dateTo   = document.getElementById('movTo').value;

        const routes = {
            pdf:   '{{ route("reports.movements.pdf") }}',
            excel: '{{ route("reports.movements.excel") }}',
            csv:   '{{ route("reports.movements.csv") }}',
        };

        const params = new URLSearchParams();
        if (type)     params.append('type',      type);
        if (dateFrom) params.append('date_from', dateFrom);
        if (dateTo)   params.append('date_to',   dateTo);

        const query = params.toString();
        window.location.href = routes[format] + (query ? '?' + query : '');
    }
    </script>

</x-layouts.app>