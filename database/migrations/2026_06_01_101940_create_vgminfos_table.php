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
        Schema::create('vgminfos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('container_id')->constrained('containers_uploads')->onDelete('cascade');

            $table->decimal('vgm_weight',10,2);
            $table->decimal('container_weight',10,2);
            $table->decimal('gross_weight',10,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vgminfos');
    }
};
