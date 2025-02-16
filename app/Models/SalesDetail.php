<?php

namespace App\Models;

use App\Models\User;
use App\Models\Sales;
use App\Models\Product;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesDetail extends Model
{
    /** @use HasFactory<\Database\Factories\SalesDetailFactory> */
    use HasFactory, SoftDeletes;

    //table
    protected $table = 'sales_detail';

    //fillable
    protected $guarded = ['id'];

    //connect to sales detail
    public function sales(): HasMany
    {
        return $this->hasMany(ReportSales::class, 'sales_id');
    }
    // SalesDetail.php
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded('*');
    }
}
