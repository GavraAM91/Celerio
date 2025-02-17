<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockProduct extends Model
{
    /** @use HasFactory<\Database\Factories\StockProductFactory> */
    use HasFactory, SoftDeletes;

    protected $tables = 'stock_products';
    protected $guarded = ['id'];

    // Relasi ke produk
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_code', 'product_code');
    }

    //log activity
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded('*');
    }
}
