<?php

namespace App\Models;

use App\Models\Sales;
use App\Models\SalesReport;
use App\Models\CategoryProduct;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    //table
    protected $table = 'products';

    //fillable
    protected $guarded = ['id'];

    //connect to category
    public function categoryProduct(): BelongsTo
    {
        return $this->belongsTo(CategoryProduct::class, 'category_id');
    }

    //connect to sales_detail 
    public function SalesDetail(): HasMany
    {
        return $this->hasMany(SalesDetail::class, 'product_id', 'id');
    }

    //log activity
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded('*');
    }
}
