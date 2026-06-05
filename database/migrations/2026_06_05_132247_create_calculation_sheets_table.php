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
        Schema::create('calculation_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id');

            $table->decimal('tcmb',10,4)
                ->nullable();

            $table->decimal('shipping_cost',15,2)
                ->default(0);

            $table->decimal('percentage',5,2)
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calculation_sheets');
    }
};
