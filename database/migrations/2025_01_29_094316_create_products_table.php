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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            //connect to category Product 
            $table->foreignId('category_id')
                ->constrained(
                    table: 'category_products'
                );

            $table->string('product_name');
            $table->string('product_image', 500)->nullable();
            $table->unsignedBigInteger('product_price');
            $table->integer('stock');
            $table->string('access_role');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
