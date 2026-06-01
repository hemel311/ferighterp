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
        Schema::create('tr_packing_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tr_packing_list_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('product_name');

            $table->text('description')->nullable();

            $table->integer('total_pallets')->nullable();

            $table->integer('total_packages')->nullable();

            $table->integer('quantity_per_unit')->default(0);

            $table->integer('item_quantity')->default(0);

            $table->decimal('gross_weight',12,2)->default(0);

            $table->decimal('net_weight',12,2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_packing_list_items');
    }
};
