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
        Schema::create('sales_detail', function (Blueprint $table) {
            $table->id();
            //connect to sales
            $table->foreignId('sales_id')
                ->constrained(
                    table: 'report_sales'
                )->onDelete('cascade');

            //connect to product
            $table->foreignId('product_id')
                ->constrained(
                    table: 'products'
                )->onDelete('cascade');

            $table->string('invoice_sales');
            $table->integer('quantity');
            $table->decimal('selling_price', 10, 2);
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
