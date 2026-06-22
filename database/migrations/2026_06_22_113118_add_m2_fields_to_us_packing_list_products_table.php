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
            $table->boolean('is_m2')
                ->default(0)
                ->after('warehouse_code');

            $table->decimal('total_m2',10,2)
                ->nullable()
                ->after('is_m2');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('us_packing_list_products', function (Blueprint $table) {
            $table->dropColumn([
                'is_m2',
                'total_m2'
            ]);

        });
    }
};
