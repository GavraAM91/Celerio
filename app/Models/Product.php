<?php

namespace App\Models;

use App\Models\Sales;
use App\Models\CategoryProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory; 

    //table
    protected $table = 'products';

    //fillable
    protected $guarded = ['id'];

    //connect to category
    public function category(): BelongsTo {
        return $this->belongsTo(CategoryProduct::class, 'category_id');
    }

    //connect to sales detail 
    public function sales(): HasMany
     {
        return $this->hasMany(Sales::class, 'sales_id');
    }

    //connect to sales_detail 
    public function salesDetail(): HasMany 
    {
        return $this->hasMany(Sales_Detail::class, 'sales_detail_id');
    }

    
}
