<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sales extends Model
{
    /** @use HasFactory<\Database\Factories\SalesFactory> */
    use HasFactory;

    //table
    protected $table = 'sales';

    //fillable
    protected $guarded = ['id'];

    //connect to user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //connect to products
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'product_id');
    }

    //connect to coupon
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }
}
