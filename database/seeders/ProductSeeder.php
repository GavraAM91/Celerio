<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\UnitOfGoods;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Gelang Rotan', 'category_id' => 1, 'unit_id' => '1', 'price' => 30000],
            ['name' => 'Kursi Rotan', 'category_id' => 2, 'unit_id' => '2', 'price' => 200000],
            ['name' => 'Meja Rotan', 'category_id' => 2, 'unit_id' => '3', 'price' => 50000],
            ['name' => 'Rotan', 'category_id' => 1, 'unit_id' => '2', 'price' => 1500]
        ];

        foreach ($products as $product) {
            $unit = UnitOfGoods::where('id', $product['unit_id'])->first();

            // Membuat kode produk otomatis PRD + 3 huruf pertama dari nama + tanggal expired
            $productCode = 'PRD' . strtoupper(Str::substr($product['name'], 0, 3)) . now()->format('Ymd')
                . Carbon::now()->addMonth()->format('Ymd');

            $newProduct = Product::create([
                'category_id' => $product['category_id'],
                'unit_id' => $product['unit_id'],
                'product_code' => $productCode, // Menggunakan kode produk yang baru
                'product_name' => $product['name'],
                'product_price' => $product['price'],
                'product_status' => 'available',
                'access_role' => 'admin',
                'created_by' => 'Seeder',
            ]);
        }
    }
}
