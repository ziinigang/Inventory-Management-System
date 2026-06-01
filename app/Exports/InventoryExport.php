<?php

namespace App\Exports;

use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventoryExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    ShouldAutoSize
{
    public function collection()
    {
        return Inventory::with(['product.supplier'])
                        ->join('products',
                               'inventories.product_id', '=', 'products.id')
                        ->select('inventories.*')
                        ->orderBy('products.name')
                        ->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'SKU',
            'Product Name',
            'Category',
            'Supplier',
            'Location',
            'Qty in Stock',
            'Reorder Level',
            'Status',
            'Last Updated',
        ];
    }

    public function map($row): array
    {
        static $index = 0;
        $index++;

        $qty     = $row->quantity;
        $reorder = $row->product->reorder_level;

        if ($qty === 0)         $status = 'Out of Stock';
        elseif ($qty <= $reorder) $status = 'Low Stock';
        else                    $status = 'In Stock';

        return [
            $index,
            $row->product->sku,
            $row->product->name,
            $row->product->category,
            $row->product->supplier->name ?? '—',
            $row->location ?? '—',
            $qty,
            $reorder,
            $status,
            $row->updated_at->format('Y-m-d H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Bold the header row
            1 => [
                'font'      => ['bold' => true, 'size' => 11],
                'fill'      => [
                    'fillType'   => 'solid',
                    'startColor' => ['rgb' => '1E293B'],
                ],
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            ],
        ];
    }

    public function title(): string
    {
        return 'Inventory Report';
    }
}