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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name_coupon');
            $table->string('coupon_description', 500);
            $table->unsignedBigInteger('value_coupon')->nullable();
            $table->unsignedTinyInteger('percentage_coupon')->nullable();
            $table->unsignedBigInteger('minimum_usage_coupon')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->integer('total_coupon');
            $table->integer('used_coupon')->nullable();
            $table->string('status')->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
