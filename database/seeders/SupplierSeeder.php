<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name'           => 'ABC Trading Corp',
                'contact_person' => 'Juan dela Cruz',
                'email'          => 'abc@trading.com',
                'phone'          => '09171234567',
                'address'        => 'Makati City, Metro Manila',
                'status'         => 'active',
            ],
            [
                'name'           => 'XYZ Supplies Inc',
                'contact_person' => 'Maria Santos',
                'email'          => 'xyz@supplies.com',
                'phone'          => '09289876543',
                'address'        => 'Cebu City, Cebu',
                'status'         => 'active',
            ],
            [
                'name'           => 'Global Goods PH',
                'contact_person' => 'Pedro Reyes',
                'email'          => 'global@goods.ph',
                'phone'          => '09351112233',
                'address'        => 'Davao City, Davao del Sur',
                'status'         => 'active',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}