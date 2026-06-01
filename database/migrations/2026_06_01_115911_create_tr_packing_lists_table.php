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
        Schema::create('tr_packing_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete();

            $table->foreignId('container_upload_id')->constrained()->cascadeOnDelete();

            $table->foreignId('vgm_info_id')
                ->nullable()
                ->constrained('vgminfos')
                ->nullOnDelete();

            $table->date('pl_date');

            $table->string('from_location')->nullable();

            $table->string('to_location')->nullable();

            $table->decimal('total_gross_weight',12,2)->default(0);

            $table->decimal('total_net_weight',12,2)->default(0);

            $table->integer('total_pallets')->default(0);

            $table->integer('total_packages')->default(0);

            $table->integer('total_item_quantity')->default(0);

            $table->enum('status',['draft','submitted'])
                ->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_packing_lists');
    }
};
