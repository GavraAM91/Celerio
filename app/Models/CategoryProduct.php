<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryProductFactory> */
    use HasFactory;

    protected $table = 'category_products';

    protected $guarded = ['id'];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
