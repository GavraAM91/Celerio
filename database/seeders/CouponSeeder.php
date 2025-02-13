<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeder pertama dengan value_coupon
        Coupon::create([
            'name_coupon' => 'MERDEKABELANJA',
            'coupon_description' => 'Diskon Rp 10.000 untuk pembelian minimal Rp 50.000',
            'value_coupon' => 10000, // Diskon dalam bentuk nominal
            'percentage_coupon' => null, // Tidak ada diskon persentase
            'minimum_usage_coupon' => 50000, // Minimal belanja
            'total_coupon' => 10, // Total kupon tersedia
            'used_coupon' => 1, // Kupon yang sudah digunakan
            'status' => 'available',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seeder kedua dengan percentage_coupon
        Coupon::create([
            'name_coupon' => 'DISKON20PERCENT',
            'coupon_description' => 'Diskon 20% untuk pembelian minimal Rp 100.000',
            'value_coupon' => null, // Tidak ada diskon nominal
            'percentage_coupon' => 20, // Diskon dalam bentuk persentase
            'minimum_usage_coupon' => 100000, // Minimal belanja
            'total_coupon' => 20, // Total kupon tersedia
            'used_coupon' => 0, // Belum digunakan
            'status' => 'available',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
