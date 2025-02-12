<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $admin = User::create([
        //     'name' => 'admin',
        //     'password' => Hash::make('password'),
        //     'email' => 'admin@example.com',
        //     'role' => 'admin',
        // ]);

        $casier = User::create([
            'name' => 'casier',
            'password' => Hash::make('password'),
            'email' => 'casier@example.com',
            'role' => 'casier',
        ]);
        $casier->assignRole('casier');

        // $admin->assignRole('admin');

    }
}
