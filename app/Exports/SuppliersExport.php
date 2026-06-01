<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SuppliersExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    ShouldAutoSize
{
    public function collection()
    {
        return Supplier::withCount('products')->orderBy('name')->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Company Name',
            'Contact Person',
            'Email',
            'Phone',
            'Address',
            'Status',
            'Total Products',
            'Date Added',
        ];
    }

    public function map($supplier): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $supplier->name,
            $supplier->contact_person,
            $supplier->email,
            $supplier->phone,
            $supplier->address,
            ucfirst($supplier->status),
            $supplier->products_count,
            $supplier->created_at->format('Y-m-d'),
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
        return 'Suppliers Report';
    }
}