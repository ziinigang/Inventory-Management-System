<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StockMovementController extends Controller
{
    // dito makikita ang lahat ng stock movements, may search at filter din
    public function index(Request $request)
    {
        $query = StockMovement::with(['product', 'user']);

        // kapag naghanap ang user ng produkto
        if ($request->filled('search')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        // kapag nag-filter ng uri ng movement, in out o adjustment
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // kapag nag-filter ng petsa ng simula
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // kapag nag-filter ng petsa ng katapusan
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $movements = $query->latest()->paginate(15);

        // mga bilang na ipapakita sa summary cards
        $stats = [
            // kabuuang units na pumasok
            'total_in'  => StockMovement::where('type', 'in')->sum('quantity'),
            // kabuuang units na lumabas
            'total_out' => StockMovement::where('type', 'out')->sum('quantity'),
            // ilang movements ang nangyari ngayong araw
            'today'     => StockMovement::whereDate('created_at', today())->count(),
        ];

        return view('stock-movements.index', compact('movements', 'stats'));
    }

    // dito ipapakita ang form para mag-record ng bagong movement
    // kapag may product_id sa URL, awtomatiko na itong mapipili sa form
    public function create(Request $request)
    {
        $products = Product::with('inventory')->orderBy('name')->get();

        // tingnan kung may pinili nang produkto mula sa inventory page
        $selectedProduct = $request->filled('product_id')
                           ? Product::with('inventory')->find($request->product_id)
                           : null;

        return view('stock-movements.create', compact('products', 'selectedProduct'));
    }

    // dito ini-save ang movement at ina-update ang stock ng produkto
    public function store(Request $request)
    {
        // i-check muna kung tama ang mga pinasok ng user
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type'       => ['required', 'in:in,out,adjustment'],
            'quantity'   => ['required', 'integer', 'not_in:0'],
            'reason'     => ['nullable', 'string', 'max:255'],
        ]);

        $product   = Product::with('inventory')->findOrFail($validated['product_id']);
        $inventory = $product->inventory;

        // siguraduhin na ang produkto ay may inventory record bago mag-proceed
        if (!$inventory) {
            return back()->withErrors([
                'product_id' => 'Ang produktong ito ay walang inventory record. Makipag-ugnayan sa admin.',
            ])->withInput();
        }

        $currentQty = $inventory->quantity;
        $moveQty    = (int) $validated['quantity'];

        // alamin kung magkano ang magiging bagong quantity
        if ($validated['type'] === 'in') {
            // stock in, dagdag lang sa current
            $newQty = $currentQty + abs($moveQty);

        } elseif ($validated['type'] === 'out') {
            // stock out, ibabawas sa current
            $newQty = $currentQty - abs($moveQty);

            // huwag payagan kung mas malaki ang ilalabas kaysa sa meron
            if ($newQty < 0) {
                throw ValidationException::withMessages([
                    'quantity' => "Hindi sapat ang stock. Available pa lang: {$currentQty} units.",
                ]);
            }
        } else {
            // adjustment, pwedeng positibo o negatibo
            $newQty = $currentQty + $moveQty;

            // huwag payagan kung magiging negatibo ang stock
            if ($newQty < 0) {
                throw ValidationException::withMessages([
                    'quantity' => "Magiging negatibo ang stock kapag itinuloy ito. 
                                   Kasalukuyan: {$currentQty} units.",
                ]);
            }
        }

        // i-save na ang movement sa database
        StockMovement::create([
            'product_id' => $validated['product_id'],
            'user_id'    => auth()->id(),
            'type'       => $validated['type'],
            'quantity'   => $moveQty,
            'reason'     => $validated['reason'],
        ]);

        // i-update na rin ang quantity sa inventories table
        $inventory->update(['quantity' => $newQty]);

        $typeLabel = ucfirst($validated['type']);
        return redirect()->route('stock-movements.index')
            ->with('success',
                   "{$typeLabel} ng {$moveQty} units para sa '{$product->name}' ay naitala na. Bagong stock: {$newQty}.");
    }

    // dito makikita ang detalye ng isang movement
    public function show(StockMovement $stockMovement)
    {
        $stockMovement->load(['product.inventory', 'user']);
        return view('stock-movements.show', compact('stockMovement'));
    }

    // hindi na pwedeng i-edit o i-delete ang movements
    // permanente sila para hindi mawala ang audit trail
    // kung may mali, gumawa na lang ng bagong movement para itama
}