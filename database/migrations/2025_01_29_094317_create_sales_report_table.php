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
        Schema::create('sales_report', function (Blueprint $table) {
            $table->id();
            //connect to sales
            $table->foreignId('sales_id')
                ->constrained(
                    table: 'sales'
                );

            //connect to product
            $table->foreignId('product_id')
                ->constrained(
                    table: 'products'
                );

            $table->string('invoice_sales')->unique();
            $table->integer('quantity');
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales__details');
    }
};
