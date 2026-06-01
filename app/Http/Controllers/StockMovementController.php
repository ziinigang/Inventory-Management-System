<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StockMovementController extends Controller
{
    // LIST all movements with search + filter
    public function index(Request $request)
    {
        $query = StockMovement::with(['product', 'user']);

        // Search by product name
        if ($request->filled('search')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku',  'like', '%' . $request->search . '%');
            });
        }

        // Filter by movement type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $movements = $query->latest()->paginate(15);

        // Summary stats
        $stats = [
            'total_in'  => StockMovement::where('type', 'in')->sum('quantity'),
            'total_out' => StockMovement::where('type', 'out')->sum('quantity'),
            'today'     => StockMovement::whereDate('created_at', today())->count(),
        ];

        return view('stock-movements.index', compact('movements', 'stats'));
    }

    // SHOW create form — pre-select product if passed via query string
    public function create(Request $request)
    {
        $products    = Product::with('inventory')->orderBy('name')->get();
        $selectedProduct = $request->filled('product_id')
                           ? Product::with('inventory')->find($request->product_id)
                           : null;

        return view('stock-movements.create',
                    compact('products', 'selectedProduct'));
    }

    // STORE new movement + update inventory
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

        // Guard: product must have an inventory record
        if (!$inventory) {
            return back()->withErrors([
                'product_id' => 'This product has no inventory record. Contact admin.',
            ])->withInput();
        }

        $currentQty = $inventory->quantity;
        $moveQty    = (int) $validated['quantity'];

        // Calculate new quantity based on movement type
        if ($validated['type'] === 'in') {
            $newQty = $currentQty + abs($moveQty);
        } elseif ($validated['type'] === 'out') {
            $newQty = $currentQty - abs($moveQty);
            // Guard: cannot release more stock than available
            if ($newQty < 0) {
                throw ValidationException::withMessages([
                    'quantity' => "Insufficient stock. Available: {$currentQty} units.",
                ]);
            }
        } else {
            // Adjustment: quantity can be negative (to reduce) or positive (to add)
            $newQty = $currentQty + $moveQty;
            if ($newQty < 0) {
                throw ValidationException::withMessages([
                    'quantity' => "Adjustment would result in negative stock.
                                   Current stock: {$currentQty}.",
                ]);
            }
        }

        // Save the movement record
        StockMovement::create([
            'product_id' => $validated['product_id'],
            'user_id'    => auth()->id(),
            'type'       => $validated['type'],
            'quantity'   => $moveQty,
            'reason'     => $validated['reason'],
        ]);

        // Update the inventory quantity
        $inventory->update(['quantity' => $newQty]);

        $typeLabel = ucfirst($validated['type']);
        return redirect()->route('stock-movements.index')
            ->with('success',
                   "{$typeLabel} of {$moveQty} units for
                    '{$product->name}' recorded. New stock: {$newQty}.");
    }

    // SHOW single movement detail
    public function show(StockMovement $stockMovement)
    {
        $stockMovement->load(['product.inventory', 'user']);
        return view('stock-movements.show', compact('stockMovement'));
    }

    // Movements cannot be edited or deleted — they are a permanent audit trail
    // If you need to reverse a movement, create a new opposite movement
}