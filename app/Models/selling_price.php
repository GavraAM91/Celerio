<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class selling_price extends Model
{
    /** @use HasFactory<\Database\Factories\SellingPriceFactory> */
    use HasFactory;

    //table 
    protected $table = "selling_price";

    //guarded
    protected $guarded = ['id'];

    //connection to table product 
    public function product(): HasMany {
        return $this->hasMany(Product::class, 'product_id');
    }
}
