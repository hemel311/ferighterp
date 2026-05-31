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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();

            $table->string('shipment_type')->default('FCL');

            $table->string('carrier')->nullable();

            $table->string('vessel_name')->nullable();
            $table->string('voyage')->nullable();

            $table->string('port_of_loading')->nullable();
            $table->string('port_of_discharge')->nullable();

            $table->date('etd')->nullable();
            $table->date('eta')->nullable();

            $table->dateTime('si_cut_off')->nullable();
            $table->dateTime('cy_cut_off')->nullable();

            $table->integer('container_qty')->nullable();

            $table->longText('remarks')->nullable();

            $table->enum('status',[
                'Draft',
                'Submitted',
                'Completed',
                'Cancelled'
            ])->default('Draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
