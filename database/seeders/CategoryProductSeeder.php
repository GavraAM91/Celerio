<?php

namespace Database\Seeders;

use App\Models\CategoryProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CategoryProduct::insert([
            'category_code' => 'CTG0001',
            'category_name' => 'Furnitur',
        ]);

        CategoryProduct::insert([

            'category_code' => 'CTG002',
            'category_name' => 'Pernak Pernik'
        ]);
    }
}
