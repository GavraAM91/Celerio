<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            //connect to user 
            $table->foreignId('user_id')
                ->constrained(
                    table: 'users'
                );
            //connect to product
            $table->foreignId('product_id')
                ->constrained(
                    table: 'products'
                );
            //connect to coupon (default null)
            $table->foreignId('coupon_id')
                ->nullable()
                ->constrained(
                    table: 'coupons'
                );
            $table->string('invoice_sales');
            $table->string('payment_method');
            $table->unsignedBigInteger('total_price');
            $table->integer('quantity');
            $table->float('tax');
            $table->string('access_role')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
