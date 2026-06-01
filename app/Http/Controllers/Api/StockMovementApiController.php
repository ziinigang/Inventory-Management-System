<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use App\Models\Product;
use App\Models\Inventory;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StockMovementApiController extends Controller
{
    use ApiResponseTrait;

    // GET /api/stock-movements
    public function index(Request $request)
    {
        $query = StockMovement::with([
            'product:id,name,sku',
            'user:id,name',
        ]);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $movements = $query->latest()->paginate(15);

        return $this->paginatedResponse($movements,
                                        'Stock movements retrieved successfully.');
    }

    // POST /api/stock-movements
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type'       => ['required', 'in:in,out,adjustment'],
            'quantity'   => ['required', 'integer', 'not_in:0'],
            'reason'     => ['nullable', 'string', 'max:255'],
        ]);

        $product   = Product::with('inventory')->findOrFail($validated['product_id']);
        $inventory = $product->inventory;

        if (!$inventory) {
            return $this->errorResponse(
                'No inventory record found for this product.', 422
            );
        }

        $currentQty = $inventory->quantity;
        $moveQty    = (int) $validated['quantity'];

        if ($validated['type'] === 'in') {
            $newQty = $currentQty + abs($moveQty);
        } elseif ($validated['type'] === 'out') {
            $newQty = $currentQty - abs($moveQty);
            if ($newQty < 0) {
                return $this->errorResponse(
                    "Insufficient stock. Available: {$currentQty} units.", 422
                );
            }
        } else {
            $newQty = $currentQty + $moveQty;
            if ($newQty < 0) {
                return $this->errorResponse(
                    "Adjustment would result in negative stock. Current: {$currentQty}.",
                    422
                );
            }
        }

        $movement = StockMovement::create([
            'product_id' => $validated['product_id'],
            'user_id'    => $request->user()->id,
            'type'       => $validated['type'],
            'quantity'   => $moveQty,
            'reason'     => $validated['reason'] ?? null,
        ]);

        $inventory->update(['quantity' => $newQty]);

        $movement->load(['product:id,name,sku', 'user:id,name']);

        return $this->successResponse([
            'movement'      => $movement,
            'previous_qty'  => $currentQty,
            'new_qty'       => $newQty,
        ], 'Stock movement recorded successfully.', 201);
    }

    // GET /api/stock-movements/{id}
    public function show(StockMovement $stockMovement)
    {
        $stockMovement->load(['product', 'user:id,name']);

        return $this->successResponse($stockMovement,
                                      'Stock movement retrieved successfully.');
    }
}