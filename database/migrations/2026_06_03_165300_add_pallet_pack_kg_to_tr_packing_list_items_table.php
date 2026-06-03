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
        Schema::table('tr_packing_list_items', function (Blueprint $table) {
            $table->string('pallet_pack_kg')
                ->nullable()
                ->after('quantity_per_unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tr_packing_list_items', function (Blueprint $table) {
            //
        });
    }
};
