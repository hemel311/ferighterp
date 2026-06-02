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
        Schema::create('us_packing_list_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('us_packing_list_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('product_name');

            $table->integer('total_pallets')->nullable();

            $table->integer('packages')->nullable();

            $table->decimal('qty_per_pallet', 10, 2)->nullable();

            $table->string('type')->nullable();

            $table->integer('total_item_qty')->nullable();

            $table->decimal('pallet_pack_kg', 10, 2)->nullable();

            $table->decimal('total_kg', 10, 2)->nullable();

            $table->decimal('gross_weight', 10, 2)->nullable();

            $table->string('warehouse_code')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('us_packing_list_products');
    }
};
