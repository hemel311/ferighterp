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
            $table->decimal('original_price',20,10)
                ->nullable()
                ->change();

            $table->decimal('item_price',20,8)
                ->nullable()
                ->change();

            $table->decimal('price_pi_a',20,15)
                ->nullable()
                ->change();

            $table->decimal('tl_usd',20,15)
                ->nullable()
                ->change();

            $table->decimal('shipping_additional',20,15)
                ->nullable()
                ->change();

            $table->decimal('cif_price',20,15)
                ->nullable()
                ->change();

            $table->decimal('tl_total',20,15)
                ->nullable()
                ->change();
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
