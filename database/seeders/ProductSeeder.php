<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Product;
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
        //insert into db 
        // Product::create(
        //     [
        //         'category_id'   => 1,
        //         'product_code'  => Str::upper(Str::random(10)),
        //         'product_name'  => 'Product A',
        //         'product_image' => 'images/product_a.jpg',
        //         'product_price' => 150000,
        //         'product_status' => 'available',
        //         'stock'         => 50,
        //         'sold_product'  => 10,
        //         'access_role'   => 'admin',
        //         'edited_by'     => 'Admin1',
        //         'created_by'    => 'Admin1',
        //         'expired_at'    => Carbon::now()->addMonths(6),
        //         'created_at'    => now(),
        //         'updated_at'    => now(),
        //     ],
        //     [
        //         'category_id'   => 2,
        //         'product_code'  => Str::upper(Str::random(10)),
        //         'product_name'  => 'Product B',
        //         'product_image' => 'images/product_b.jpg',
        //         'product_price' => 250000,
        //         'product_status' => 'available',
        //         'stock'         => 30,
        //         'sold_product'  => 5,
        //         'access_role'   => 'admin',
        //         'edited_by'     => 'Admin2',
        //         'created_by'    => 'Admin2',
        //         'expired_at'    => Carbon::now()->addMonths(3),
        //         'created_at'    => now(),
        //         'updated_at'    => now(),
        //     ]
        // );

        Product::create(
            [
                'category_id'   => 2,
                'product_code'  => Str::upper(Str::random(10)),
                'product_name'  => 'Lapis Legit',
                'product_image' => 'images/product_b.jpg',
                'product_price' => 250000,
                'product_status' => 'available',
                'stock'         => 30,
                'sold_product'  => 3,
                'access_role'   => 'admin',
                'edited_by'     => 'Admin2',
                'created_by'    => 'Admin2',
                'expired_at'    => Carbon::now()->addMonths(3),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]
        );
    }
}
