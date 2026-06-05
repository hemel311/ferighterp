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
        Schema::create('calculation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calculation_sheet_id');

            $table->foreignId('product_id');

            $table->json('container_quantities')
                ->nullable();

            $table->integer('invoice_qty')
                ->default(0);

            $table->decimal('original_price',12,2)
                ->nullable();

            $table->decimal('item_price',12,2)
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calculation_items');
    }
};
