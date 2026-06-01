<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class SupplierApiController extends Controller
{
    use ApiResponseTrait;

    // GET /api/suppliers
    public function index(Request $request)
    {
        $query = Supplier::withCount('products');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $suppliers = $query->latest()->paginate(15);

        return $this->paginatedResponse($suppliers,
                                        'Suppliers retrieved successfully.');
    }

    // POST /api/suppliers
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string'],
            'email'          => ['required', 'email', 'unique:suppliers,email'],
            'phone'          => ['required', 'string'],
            'address'        => ['required', 'string'],
            'status'         => ['sometimes', 'in:active,inactive'],
        ]);

        $supplier = Supplier::create($validated);

        return $this->successResponse($supplier,
                                      'Supplier created successfully.', 201);
    }

    // GET /api/suppliers/{id}
    public function show(Supplier $supplier)
    {
        $supplier->loadCount('products');
        $supplier->load(['products:id,supplier_id,name,sku,price']);

        return $this->successResponse($supplier,
                                      'Supplier retrieved successfully.');
    }

    // PUT /api/suppliers/{id}
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name'           => ['sometimes', 'string', 'max:255'],
            'contact_person' => ['sometimes', 'string'],
            'email'          => ['sometimes', 'email',
                                 'unique:suppliers,email,' . $supplier->id],
            'phone'          => ['sometimes', 'string'],
            'address'        => ['sometimes', 'string'],
            'status'         => ['sometimes', 'in:active,inactive'],
        ]);

        $supplier->update($validated);

        return $this->successResponse($supplier,
                                      'Supplier updated successfully.');
    }

    // DELETE /api/suppliers/{id}
    public function destroy(Supplier $supplier)
    {
        if ($supplier->products()->count() > 0) {
            return $this->errorResponse(
                'Cannot delete supplier with existing products.', 422
            );
        }

        $name = $supplier->name;
        $supplier->delete();

        return $this->successResponse(null,
                                      "Supplier '{$name}' deleted successfully.");
    }
}