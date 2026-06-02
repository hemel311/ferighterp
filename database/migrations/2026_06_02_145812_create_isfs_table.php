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
        Schema::create('isfs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('shipment_id');
            $table->unsignedBigInteger('mbl_prefix_id');

            $table->string('booking_number');

            $table->text('product_name')->nullable();
            $table->text('hs_code')->nullable();

            $table->string('hbl')->nullable();
            $table->string('mbl')->nullable();

            $table->date('etd')->nullable();

            $table->text('port_of_loading')->nullable();
            $table->text('port_of_discharge')->nullable();

            $table->text('container_numbers')->nullable();
            $table->string('vessel_name')->nullable();
            $table->string('voyage')->nullable();

            $table->enum('status', ['Draft', 'Submitted'])
                ->default('Draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('isfs');
    }
};
