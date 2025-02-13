<?php

namespace Database\Seeders;

use App\Models\SellingPrice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SellingPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SellingPrice::create(
            [
                'type_buyer' => 'type1',
                'selling_price' => 1.10
            ],
        );
        SellingPrice::create([
            'type_buyer' => 'type2',
            'selling_price' => 1.20
        ]);

        SellingPrice::create([
            'type_buyer' => 'type3',
            'selling_price' => 1.30
        ],);
    }
}
