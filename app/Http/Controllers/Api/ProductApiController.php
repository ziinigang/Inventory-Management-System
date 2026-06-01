<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\StockMovement;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    use ApiResponseTrait;

    // GET /api/products
    public function index(Request $request)
    {
        $query = Product::with(['supplier:id,name', 'inventory:id,product_id,quantity']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku',  'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $products = $query->latest()->paginate(15);

        return $this->paginatedResponse($products, 'Products retrieved successfully.');
    }

    // POST /api/products
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id'   => ['required', 'exists:suppliers,id'],
            'name'          => ['required', 'string', 'max:255'],
            'sku'           => ['required', 'string', 'unique:products,sku'],
            'category'      => ['required', 'string'],
            'description'   => ['nullable', 'string'],
            'price'         => ['required', 'numeric', 'min:0'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'quantity'      => ['required', 'integer', 'min:0'],
            'location'      => ['nullable', 'string'],
        ]);

        $product = Product::create([
            'supplier_id'   => $validated['supplier_id'],
            'name'          => $validated['name'],
            'sku'           => $validated['sku'],
            'category'      => $validated['category'],
            'description'   => $validated['description'] ?? null,
            'price'         => $validated['price'],
            'reorder_level' => $validated['reorder_level'],
        ]);

        Inventory::create([
            'product_id' => $product->id,
            'quantity'   => $validated['quantity'],
            'location'   => $validated['location'] ?? null,
        ]);

        if ($validated['quantity'] > 0) {
            StockMovement::create([
                'product_id' => $product->id,
                'user_id'    => $request->user()->id,
                'type'       => 'in',
                'quantity'   => $validated['quantity'],
                'reason'     => 'Initial stock via API',
            ]);
        }

        $product->load(['supplier:id,name', 'inventory:id,product_id,quantity,location']);

        return $this->successResponse($product, 'Product created successfully.', 201);
    }

    // GET /api/products/{id}
    public function show(Product $product)
    {
        $product->load(['supplier', 'inventory',
                        'stockMovements' => fn($q) => $q->latest()->take(5)]);

        return $this->successResponse($product, 'Product retrieved successfully.');
    }

    // PUT /api/products/{id}
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'supplier_id'   => ['sometimes', 'exists:suppliers,id'],
            'name'          => ['sometimes', 'string', 'max:255'],
            'sku'           => ['sometimes', 'string',
                                'unique:products,sku,' . $product->id],
            'category'      => ['sometimes', 'string'],
            'description'   => ['nullable', 'string'],
            'price'         => ['sometimes', 'numeric', 'min:0'],
            'reorder_level' => ['sometimes', 'integer', 'min:0'],
        ]);

        $product->update($validated);
        $product->load(['supplier:id,name', 'inventory:id,product_id,quantity']);

        return $this->successResponse($product, 'Product updated successfully.');
    }

    // DELETE /api/products/{id}
    public function destroy(Product $product)
    {
        $name = $product->name;
        $product->delete();

        return $this->successResponse(null, "Product '{$name}' deleted successfully.");
    }
}