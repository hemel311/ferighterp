<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('us_packing_lists', function (Blueprint $table) {

            $table->id();

            $table->foreignId('container_upload_id')
                ->constrained('container_uploads')
                ->cascadeOnDelete();

            $table->enum('status', [
                'draft',
                'submitted'
            ])->default('draft');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('us_packing_lists');
    }
};