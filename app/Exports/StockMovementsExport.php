<?php

namespace App\Exports;

use App\Models\StockMovement;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockMovementsExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    ShouldAutoSize
{
    public function __construct(
        private ?string $type      = null,
        private ?string $dateFrom  = null,
        private ?string $dateTo    = null,
        private ?int    $productId = null
    ) {}

    public function query()
    {
        $query = StockMovement::with(['product:id,name,sku', 'user:id,name'])
                              ->latest();

        if ($this->type) {
            $query->where('type', $this->type);
        }
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }
        if ($this->productId) {
            $query->where('product_id', $this->productId);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Ref #',
            'Date',
            'Time',
            'Product Name',
            'SKU',
            'Type',
            'Quantity',
            'Reason',
            'Recorded By',
        ];
    }

    public function map($movement): array
    {
        return [
            $movement->id,
            $movement->created_at->format('Y-m-d'),
            $movement->created_at->format('h:i A'),
            $movement->product->name,
            $movement->product->sku,
            ucfirst($movement->type),
            $movement->type === 'out'
                ? '-' . abs($movement->quantity)
                : '+' . abs($movement->quantity),
            $movement->reason ?? '—',
            $movement->user->name ?? '—',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold'  => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType'   => 'solid',
                    'startColor' => ['rgb' => '1E293B'],
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Stock Movements';
    }
}