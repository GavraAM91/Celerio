<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\PermissionsModel;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //create role
        // Role::create(['name' => 'admin']);
        // Role::create(['name' => 'casier']);

        // //product permission
        // Permission::create(['name' => 'read-product']);
        // Permission::create(['name' => 'create-product']);
        // Permission::create(['name' => 'edit-product']);
        // Permission::create(['name' => 'delete-product']);

        // //membership
        // Permission::create(['name' => 'read-membership']);
        // Permission::create(['name' => 'create-membership']);
        // Permission::create(['name' => 'edit-membership']);
        // Permission::create(['name' => 'delete-membership']);

        // //coupon
        // Permission::create(['name' => 'read-coupon']);
        // Permission::create(['name' => 'create-coupon']);
        // Permission::create(['name' => 'edit-coupon']);
        // Permission::create(['name' => 'delete-coupon']);
        // Permission::create(['name' => 'use-coupon']);

        // $adminRole = Role::firstOrCreate(['name' => 'admin']);
        // $casierRole = Role::firstOrCreate(['name' => 'casier']);

        // Assign permission ke role admin

        $adminRoles = Role::where('name', 'admin')->get();
        foreach ($adminRoles as $role) {
            $role->givePermissionTo([
                'read-membership',
                'create-membership',
                'edit-membership',
                'delete-membership',
            ]);
        }

        $casierRoles = Role::where('name', 'casier')->get();
        foreach ($casierRoles as $role) {
            $role->givePermissionTo([
                'read-membership',
                'create-membership',
                'edit-membership',
            ]);
        }
        //get all permissions from table
        // $getPermissions = PermissionsModel::all()->pluck('name')->toArray();

        // //sync permissions to admin
        // $roleAdmin = Role::findById('2');
        // $roleAdmin->assignRole('admin');
        // $roleAdmin->givePermissionTo($getPermissions);
    }
}
