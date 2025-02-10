<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;


    //table name
    protected $table = 'roles';

    //fillable
    // protected $fillable = [''];

    //guard
    protected $guarded = ['id'];

    //connect into table has_relations
    public function permisssions()
    {
        return $this->belongsToMany(PermissionsModel::class, 'roles_has_permissions');
    }
}
