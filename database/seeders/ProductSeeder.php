<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'sku' => 'PRD001',
                'barcode' => '1234567890123',
                'name' => 'Beras Premium 5kg',
                'cost_price' => 45000,
                'sell_price' => 55000,
                'stock' => 50,
                'track_batch' => true,
            ],
            [
                'sku' => 'PRD002',
                'barcode' => '1234567890124',
                'name' => 'Minyak Goreng 2L',
                'cost_price' => 25000,
                'sell_price' => 30000,
                'stock' => 100,
                'track_batch' => true,
            ],
            [
                'sku' => 'PRD003',
                'barcode' => '1234567890125',
                'name' => 'Gula Pasir 1kg',
                'cost_price' => 12000,
                'sell_price' => 15000,
                'stock' => 75,
                'track_batch' => false,
            ],
            [
                'sku' => 'PRD004',
                'barcode' => '1234567890126',
                'name' => 'Telur Ayam 1kg',
                'cost_price' => 20000,
                'sell_price' => 25000,
                'stock' => 30,
                'track_batch' => true,
            ],
            [
                'sku' => 'PRD005',
                'barcode' => '1234567890127',
                'name' => 'Susu UHT 1L',
                'cost_price' => 15000,
                'sell_price' => 18000,
                'stock' => 60,
                'track_batch' => true,
            ],
            [
                'sku' => 'PRD006',
                'barcode' => '1234567890128',
                'name' => 'Sabun Mandi 100gr',
                'cost_price' => 5000,
                'sell_price' => 7000,
                'stock' => 120,
                'track_batch' => false,
            ],
            [
                'sku' => 'PRD007',
                'barcode' => '1234567890129',
                'name' => 'Shampoo 250ml',
                'cost_price' => 18000,
                'sell_price' => 22000,
                'stock' => 80,
                'track_batch' => false,
            ],
            [
                'sku' => 'PRD008',
                'barcode' => '1234567890130',
                'name' => 'Pasta Gigi 100gr',
                'cost_price' => 8000,
                'sell_price' => 10000,
                'stock' => 90,
                'track_batch' => false,
            ],
            [
                'sku' => 'PRD009',
                'barcode' => '1234567890131',
                'name' => 'Roti Tawar 400gr',
                'cost_price' => 10000,
                'sell_price' => 13000,
                'stock' => 40,
                'track_batch' => true,
            ],
            [
                'sku' => 'PRD010',
                'barcode' => '1234567890132',
                'name' => 'Air Mineral 600ml',
                'cost_price' => 3000,
                'sell_price' => 4000,
                'stock' => 200,
                'track_batch' => false,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

