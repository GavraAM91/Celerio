<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionsModel extends Model
{
    /** @use HasFactory<\Database\Factories\PermissionsFactory> */
    use HasFactory;

    //table
    protected $table = 'permissions';

    //guard 
    protected $guard = ['id'];

    //connect into table roles_has_permissions
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'roles_has_permissions');
    }
}
