<x-layouts.app title="Edit Supplier">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">Edit Supplier</h5>
            <small class="text-muted">{{ $supplier->name }}</small>
        </div>
        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent fw-semibold">
                    Supplier Information
                </div>
                <div class="card-body">
                    <form method="POST"
                          action="{{ route('suppliers.update', $supplier) }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-medium">
                                    Company Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $supplier->name) }}"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">
                                    Contact Person <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="contact_person"
                                    class="form-control @error('contact_person') is-invalid @enderror"
                                    value="{{ old('contact_person',
                                               $supplier->contact_person) }}"
                                    required>
                                @error('contact_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">
                                    Email Address <span class="text-danger">*</span>
                                </label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $supplier->email) }}"
                                    required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">
                                    Phone Number <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $supplier->phone) }}"
                                    required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-medium">
                                    Address <span class="text-danger">*</span>
                                </label>
                                <textarea name="address" rows="2"
                                    class="form-control @error('address') is-invalid @enderror"
                                    required>{{ old('address', $supplier->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active"
                                        {{ old('status', $supplier->status) === 'active'
                                           ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="inactive"
                                        {{ old('status', $supplier->status) === 'inactive'
                                           ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                            </div>

                        </div>

                        <hr class="my-4">

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('suppliers.show', $supplier) }}"
                               class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-dark px-4">
                                <i class="bi bi-check-lg me-1"></i>Update Supplier
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>