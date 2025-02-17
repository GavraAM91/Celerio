<?php

namespace Database\Seeders;

use App\Models\UnitOfGoods;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UnitOfGoodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = ['pcs', 'kg', 'liter', 'box'];

        foreach ($units as $unit) {
            UnitOfGoods::create([
                'unit' => $unit,
            ]);
        }
    }
}
