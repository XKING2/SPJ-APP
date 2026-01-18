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
         Schema::create('penerimaan_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('penerimaan_id')->nullable()
                ->constrained('penerimaans')->nullOnDelete();

            $table->foreignId('pesanan_item_id')->nullable()
                ->constrained('pesanan_items')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('satuan');
            $table->integer('harga_satuan');
            $table->integer('total');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerimaan_details');
    }
};
