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
        Schema::create('penerimaans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pemeriksaan_id');
            $table->unsignedBigInteger('pesanan_id');
            $table->unsignedBigInteger('pesanan_item_id')->nullable();
            $table->string('pekerjaan');
            $table->string('no_surat');
            $table->date('surat_dibuat')->nullable();
            $table->string('nama_pihak_kedua');
            $table->string('jabatan_pihak_kedua');
            $table->integer('subtotal');
            $table->integer('ppn');
            $table->integer('grandtotal');
            $table->integer('dibulatkan');
            $table->string('terbilang');
            $table->timestamps();


            $table->foreign('pemeriksaan_id')->references('id')->on('pemeriksaans')->onDelete('cascade');
            $table->foreign('pesanan_id')->references('id')->on('pesanans')->onDelete('cascade');
            $table->foreign('pesanan_item_id')->references('id')->on('pesanan_items')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_penerimaan');
    }
};
