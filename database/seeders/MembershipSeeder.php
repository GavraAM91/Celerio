<?php

namespace Database\Seeders;

use App\Models\Membership;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Membership::create([
            'membership_code' => 'MBRMRSH2025122',
            'username' => 'Marsha Lenathea Lapian',
            'name' => 'Marsha',
            'email' => 'marshalenathea@gmail.com',
            'address' => 'gugugaga',
            'point' => 0,
            'type' => 'type1',
            'created_at' => now(),
            'updated_at' => null,
        ]);
    }
}
