<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class InventoryApiController extends Controller
{
    use ApiResponseTrait;

    // GET /api/inventories
    public function index(Request $request)
    {
        $query = Inventory::with(['product:id,name,sku,category,reorder_level']);

        // Filter low stock items
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->join('products',
                             'inventories.product_id', '=', 'products.id')
                      ->whereRaw('inventories.quantity <= products.reorder_level')
                      ->where('inventories.quantity', '>', 0)
                      ->select('inventories.*');
            } elseif ($request->stock_status === 'out') {
                $query->where('quantity', 0);
            }
        }

        $inventories = $query->latest()->paginate(15);

        return $this->paginatedResponse($inventories,
                                        'Inventories retrieved successfully.');
    }

    // GET /api/inventories/{id}
    public function show(Inventory $inventory)
    {
        $inventory->load(['product.supplier']);

        return $this->successResponse($inventory,
                                      'Inventory record retrieved successfully.');
    }

    // PATCH /api/inventories/{id}
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'location' => ['nullable', 'string', 'max:100'],
        ]);

        $inventory->update($validated);

        return $this->successResponse($inventory,
                                      'Inventory location updated.');
    }
}