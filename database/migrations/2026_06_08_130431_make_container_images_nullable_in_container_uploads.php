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
        Schema::table('container_uploads', function (Blueprint $table) {
            $table->string('container_image')
                ->nullable()
                ->change();

            $table->string('seal_image')
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('container_uploads', function (Blueprint $table) {
            $table->string('container_image')
                ->nullable(false)
                ->change();

            $table->string('seal_image')
                ->nullable(false)
                ->change();
        });
    }
};
