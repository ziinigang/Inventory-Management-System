@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 bg-primary bg-opacity-10">
                        <i class="bi bi-box fs-4 text-primary"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Products</div>
                        <div class="fw-bold fs-4">0</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 bg-success bg-opacity-10">
                        <i class="bi bi-truck fs-4 text-success"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Suppliers</div>
                        <div class="fw-bold fs-4">0</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 bg-warning bg-opacity-10">
                        <i class="bi bi-exclamation-triangle fs-4 text-warning"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Low Stock Items</div>
                        <div class="fw-bold fs-4">0</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 bg-info bg-opacity-10">
                        <i class="bi bi-arrow-left-right fs-4 text-info"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Stock Movements</div>
                        <div class="fw-bold fs-4">0</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h6 class="fw-semibold mb-1">Welcome, {{ auth()->user()->name }}</h6>
            <p class="text-muted mb-0 small">
                You are logged in as <strong>{{ auth()->user()->role }}</strong>.
                Use the sidebar to navigate.
            </p>
        </div>
    </div>

@endsection