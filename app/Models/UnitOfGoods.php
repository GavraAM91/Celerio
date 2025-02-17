<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitOfGoods extends Model
{
    /** @use HasFactory<\Database\Factories\UnitOfGoodsFactory> */
    use HasFactory, SoftDeletes;


    protected $table = 'unit_of_goods';
    protected $guarded = ['id'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'unit_id', 'id');
    }
}
