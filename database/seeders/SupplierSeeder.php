<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'PT Sumber Makmur',
                'phone' => '021-12345678',
                'address' => 'Jl. Raya Jakarta No. 123',
            ],
            [
                'name' => 'CV Berkah Jaya',
                'phone' => '021-87654321',
                'address' => 'Jl. Sudirman No. 456',
            ],
            [
                'name' => 'UD Sejahtera',
                'phone' => '021-11223344',
                'address' => 'Jl. Thamrin No. 789',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}

