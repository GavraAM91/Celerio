<?php

namespace App\Models;

use App\Models\PermissionsModel;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded('*');
    }
}
