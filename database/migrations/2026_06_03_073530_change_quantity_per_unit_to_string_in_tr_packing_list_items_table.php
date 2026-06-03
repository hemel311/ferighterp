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
            $table->string('quantity_per_unit')
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tr_packing_list_items', function (Blueprint $table) {
            $table->decimal('quantity_per_unit',10,2)
                ->nullable()
                ->change();
        });
    }
};
