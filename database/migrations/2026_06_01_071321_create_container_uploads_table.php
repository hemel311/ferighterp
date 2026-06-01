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
        Schema::create('container_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number');

            $table->integer('container_serial');

            $table->string('container_number')->nullable();
            $table->string('seal_number')->nullable();

            $table->string('container_image');
            $table->string('seal_image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('container_uploads');
    }
};
