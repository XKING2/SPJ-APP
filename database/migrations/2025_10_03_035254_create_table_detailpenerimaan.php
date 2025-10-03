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
            $table->unsignedBigInteger('penerimaan_id');
            $table->unsignedBigInteger('pesanan_item_id')->nullable();
            $table->string('nama_barang');
            $table->integer('jumlah');
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
        Schema::dropIfExists('table_detailpenerimaan');
    }
};
