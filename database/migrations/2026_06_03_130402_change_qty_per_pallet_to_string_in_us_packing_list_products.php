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
        Schema::table('us_packing_list_products', function (Blueprint $table) {
            $table->string('qty_per_pallet')
                ->nullable()
                ->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('us_packing_list_products', function (Blueprint $table) {
            $table->decimal('qty_per_pallet', 10, 2)
                ->nullable()
                ->change();
        });
    }
};
