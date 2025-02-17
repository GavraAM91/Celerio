<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\StockProduct;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StockProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            StockProduct::create([
                'product_id' => $product->id,
                'stock' => 100, // Jumlah stok, misalnya 100
                'expired_at' => Carbon::now()->addMonth(), // Tanggal expired 1 bulan ke depan
            ]);
        }
    }
}
