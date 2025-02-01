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
        Schema::create('selling_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignid('product_id')
            ->constrained(
                table: 'products'
            );
            $table->string('type_buyer');
            $table->float('cogs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selling_prices');
    }
};
