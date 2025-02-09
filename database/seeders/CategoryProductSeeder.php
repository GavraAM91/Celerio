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
            'category_name' => 'category 1',
            'access_role' => 'admin'
        ]);

        CategoryProduct::insert([
            'category_name' => 'category 2',
            'access_role' => 'admin'
        ]);
    }
}
