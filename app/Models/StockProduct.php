<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockProduct extends Model
{
    /** @use HasFactory<\Database\Factories\StockProductFactory> */
    use HasFactory, SoftDeletes;

    protected $tables = 'stock_products';
    protected $guarded = ['id'];

    // Relasi ke produk
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    //log activity
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded('*');
    }
}
