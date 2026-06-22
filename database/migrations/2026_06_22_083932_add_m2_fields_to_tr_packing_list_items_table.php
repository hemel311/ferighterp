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
            $table->boolean('is_m2')
                ->default(0)
                ->after('quantity_per_unit');

            $table->decimal('total_m2',12,2)
                ->nullable()
                ->after('is_m2');
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
