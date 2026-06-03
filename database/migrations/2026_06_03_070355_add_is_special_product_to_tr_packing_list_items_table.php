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
            $table->boolean('is_special_product')
                ->default(0)
                ->after('product_name');
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
