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
                )->onDelete('cascade');


            //connect to Unit Of Goods
            $table->foreignId('unit_id')
                ->constrained(
                    table: 'unit_of_goods'
                )->onDelete('restrict');

            // $table->foreignId('')
            $table->string('product_code')->unique();
            $table->string('product_name');
            $table->string('product_image', 500)->nullable();
            $table->unsignedBigInteger('product_price');
            $table->string('product_status');
            $table->integer('sold_product')->default(0);
            $table->integer('minimum_stock')->default(10);
            $table->string('access_role');
            $table->string('edited_by')->nullable();
            $table->string('created_by')->nullable();
            $table->softDeletes();
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
