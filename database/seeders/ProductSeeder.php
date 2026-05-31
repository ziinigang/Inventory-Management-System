<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'supplier_id'   => 1,
                'name'          => 'Ballpen Black',
                'sku'           => 'SKU-001',
                'category'      => 'Office Supplies',
                'description'   => 'Black ink ballpen, 0.5mm tip',
                'price'         => 12.00,
                'reorder_level' => 50,
            ],
            [
                'supplier_id'   => 1,
                'name'          => 'A4 Bond Paper (500 sheets)',
                'sku'           => 'SKU-002',
                'category'      => 'Office Supplies',
                'description'   => 'A4 size bond paper, 80gsm, 1 ream',
                'price'         => 250.00,
                'reorder_level' => 20,
            ],
            [
                'supplier_id'   => 2,
                'name'          => 'Stapler Heavy Duty',
                'sku'           => 'SKU-003',
                'category'      => 'Office Equipment',
                'description'   => 'Heavy duty stapler, capacity 50 sheets',
                'price'         => 185.00,
                'reorder_level' => 10,
            ],
            [
                'supplier_id'   => 2,
                'name'          => 'Correction Tape',
                'sku'           => 'SKU-004',
                'category'      => 'Office Supplies',
                'description'   => '5mm x 8m correction tape',
                'price'         => 35.00,
                'reorder_level' => 30,
            ],
            [
                'supplier_id'   => 3,
                'name'          => 'Whiteboard Marker',
                'sku'           => 'SKU-005',
                'category'      => 'Classroom Supplies',
                'description'   => 'Dry erase whiteboard marker, black',
                'price'         => 45.00,
                'reorder_level' => 25,
            ],
            [
                'supplier_id'   => 3,
                'name'          => 'Folder Long',
                'sku'           => 'SKU-006',
                'category'      => 'Office Supplies',
                'description'   => 'Plastic long folder, assorted colors',
                'price'         => 18.00,
                'reorder_level' => 40,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}