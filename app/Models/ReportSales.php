<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;

class ReportSales extends Model
{
    /** @use HasFactory<\Database\Factories\SalesFactory> */
    use HasFactory;

    //table
    protected $table = 'report_sales';

    //fillable
    protected $guarded = ['id'];

    //connect to user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id',  'id');
    }

    //connect to products
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'product_id');
    }

    //connect to membership
    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class, 'membership_id', 'id');
    }

    //connect to coupon
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id', 'id');
    }

//connect to salesdetails
    public function salesDetails(): HasMany
    {
        return $this->hasMany(SalesDetail::class, 'sales_id', 'id');
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded('*');
    }
}
