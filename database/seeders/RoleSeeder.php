<?php

namespace Database\Seeders;

use App\Models\PermissionsModel;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //create role
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'casier']);

        // product permission
        Permission::create(['name' => 'see-product']);
        Permission::create(['name' => 'add-product']);
        Permission::create(['name' => 'edit-product']);
        Permission::create(['name' => 'delete-product']);

        //membership
        Permission::create(['name' => 'lihat-user']);
        Permission::create(['name' => 'tambah-user']);
        Permission::create(['name' => 'edit-user']);
        Permission::create(['name' => 'delete-user']);

        //coupon
        Permission::create(['name' => 'see-coupon']);
        Permission::create(['name' => 'add-coupon']);
        Permission::create(['name' => 'edit-coupon']);
        Permission::create(['name' => 'delete-coupon']);
        Permission::create(['name' => 'use-coupon']);

        //get all permissions from table
        // $getPermissions = PermissionsModel::all()->pluck('name')->toArray();

        // //sync permissions to admin
        // $roleAdmin = Role::findByName('admin');
        // $roleAdmin->givePermissionTo($getPermissions);
    }
}
