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
        Schema::table('calculation_items', function (Blueprint $table) {
            $table->decimal('price_pi_a',15,4)
                ->nullable();

            $table->decimal('tl_usd',15,4)
                ->nullable();

            $table->decimal('shipping_additional',15,4)
                ->nullable();

            $table->decimal('cif_price',15,4)
                ->nullable();

            $table->decimal('tl_total',15,2)
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calculation_items', function (Blueprint $table) {
            //
        });
    }
};
