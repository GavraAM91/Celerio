<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'user',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RoleSeeder::class,
            CategoryProductSeeder::class,
            UserSeeder::class,
            UnitOfGoodsSeeder::class,
            ProductSeeder::class,
            StockProductSeeder::class,
            SellingPriceSeeder::class,
            MembershipSeeder::class,
            CouponSeeder::class,
            // AssignRoleSeeder::class,
        ]);
    }
}
