<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\Product;
use App\Exports\InventoryExport;
use App\Exports\StockMovementsExport;
use App\Exports\SuppliersExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Reports dashboard
    public function index()
    {
        $summary = [
            'total_products'   => Product::count(),
            'total_suppliers'  => Supplier::count(),
            'low_stock_count'  => Inventory::join('products',
                                      'inventories.product_id', '=', 'products.id')
                                      ->whereRaw('inventories.quantity
                                                  <= products.reorder_level')
                                      ->count(),
            'out_of_stock'     => Inventory::where('quantity', 0)->count(),
            'movements_today'  => StockMovement::whereDate('created_at',
                                      today())->count(),
            'movements_month'  => StockMovement::whereMonth('created_at',
                                      now()->month)->count(),
        ];

        return view('reports.pdf.index', compact('summary'));
    }

    // ─── INVENTORY REPORTS ──────────────────────────────────────────────────

    public function inventoryPdf()
    {
        $inventories = Inventory::with(['product.supplier'])
                                ->join('products',
                                       'inventories.product_id', '=', 'products.id')
                                ->select('inventories.*')
                                ->orderBy('products.name')
                                ->get();

        $inStock    = $inventories->filter(
                          fn($i) => $i->quantity > $i->product->reorder_level
                      )->count();
        $lowStock   = $inventories->filter(
                          fn($i) => $i->quantity > 0
                                 && $i->quantity <= $i->product->reorder_level
                      )->count();
        $outOfStock = $inventories->filter(
                          fn($i) => $i->quantity === 0
                      )->count();
        $totalValue = $inventories->sum(
                          fn($i) => $i->quantity * ($i->product->price ?? 0)
                      );

        $pdf = Pdf::loadView('reports.pdf.inventory', compact(
            'inventories', 'inStock', 'lowStock', 'outOfStock', 'totalValue'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('inventory-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function inventoryExcel()
    {
        $filename = 'inventory-report-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new InventoryExport(), $filename);
    }

    public function inventoryCsv()
    {
        $filename = 'inventory-report-' . now()->format('Y-m-d') . '.csv';
        return Excel::download(new InventoryExport(), $filename,
                               \Maatwebsite\Excel\Excel::CSV);
    }

    // ─── STOCK MOVEMENT REPORTS ─────────────────────────────────────────────

    public function movementsPdf(Request $request)
    {
        $query = StockMovement::with(['product', 'user'])->latest();

        $filters = [
            'type'      => $request->type,
            'date_from' => $request->date_from,
            'date_to'   => $request->date_to,
        ];

        if ($filters['type'])      $query->where('type', $filters['type']);
        if ($filters['date_from']) $query->whereDate('created_at', '>=',
                                                     $filters['date_from']);
        if ($filters['date_to'])   $query->whereDate('created_at', '<=',
                                                     $filters['date_to']);

        $movements = $query->get();

        $pdf = Pdf::loadView('reports.pdf.stock-movements',
                             compact('movements', 'filters'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('stock-movements-' . now()->format('Y-m-d') . '.pdf');
    }

    public function movementsExcel(Request $request)
    {
        $export   = new StockMovementsExport(
            $request->type,
            $request->date_from,
            $request->date_to,
            $request->product_id
        );
        $filename = 'stock-movements-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download($export, $filename);
    }

    public function movementsCsv(Request $request)
    {
        $export   = new StockMovementsExport(
            $request->type,
            $request->date_from,
            $request->date_to
        );
        $filename = 'stock-movements-' . now()->format('Y-m-d') . '.csv';
        return Excel::download($export, $filename,
                               \Maatwebsite\Excel\Excel::CSV);
    }

    // ─── SUPPLIER REPORTS ───────────────────────────────────────────────────

    public function suppliersPdf()
    {
        $suppliers = Supplier::withCount('products')->orderBy('name')->get();

        $pdf = Pdf::loadView('reports.pdf.suppliers', compact('suppliers'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('suppliers-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function suppliersExcel()
    {
        $filename = 'suppliers-report-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new SuppliersExport(), $filename);
    }

    public function suppliersCsv()
    {
        $filename = 'suppliers-report-' . now()->format('Y-m-d') . '.csv';
        return Excel::download(new SuppliersExport(), $filename,
                               \Maatwebsite\Excel\Excel::CSV);
    }
}