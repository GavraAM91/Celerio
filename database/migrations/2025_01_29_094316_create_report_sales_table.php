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
        Schema::create('report_sales', function (Blueprint $table) {
            $table->id();

            //connect to user 
            $table->foreignId('user_id')
                ->constrained(
                    table: 'users'
                );
            //connect to product
            $table->foreignId('membership_id')
                ->nullable()
                ->constrained(
                    table: 'memberships'
                );
            //connect to coupon (default null)
            $table->foreignId('coupon_id')
                ->nullable()
                ->constrained(
                    table: 'coupons'
                );
            $table->string('invoice_sales')->unique();
            $table->string('membership_name');
            $table->decimal('tax', 10, 2);
            $table->decimal('total_product_price', 10, 2);
            $table->decimal('total_price_discount', 10, 2)->nullable();
            $table->decimal('final_price', 10, 2);
            $table->decimal('cash_received', 10, 2);
            $table->decimal('change', 10, 2);
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
