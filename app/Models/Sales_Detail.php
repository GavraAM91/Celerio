<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sales_Detail extends Model
{
    /** @use HasFactory<\Database\Factories\SalesDetailFactory> */
    use HasFactory;

    //table
    protected $table = 'sales_detail';

    //fillable
    protected $guarded = ['id'];

    //connect to sales detail
    public function sales(): HasMany
    {
        return $this->hasMany(Sales::class, 'sales_id');
    }

    //connect to product
    public function product(): HasMany
    {
        return $this->hasMany(Product::class, 'product_id');
    }
}
