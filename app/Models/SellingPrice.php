<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellingPrice extends Model
{
    /** @use HasFactory<\Database\Factories\SellingPriceFactory> */
    use HasFactory, SoftDeletes;

    //table 
    protected $table = "selling_prices";

    //guarded
    protected $guarded = ['id'];

    //connection to table product 
    // public function product(): HasMany
    // {
    //     return $this->hasMany(Product::class, 'product_id');
    // }
}
