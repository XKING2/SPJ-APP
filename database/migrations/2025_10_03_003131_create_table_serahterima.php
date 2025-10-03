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
        Schema::create('serahterimas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pemeriksaan_id');
            $table->unsignedBigInteger('kwitansi_id');
            $table->unsignedBigInteger('pesanan_id');
            $table->unsignedBigInteger('penerimaan_id');
            $table->timestamps();
            $table->foreign('pemeriksaan_id')->references('id')->on('pemeriksaans')->onDelete('cascade');
            $table->foreign('kwitansi_id')->references('id')->on('kwitansis')->onDelete('cascade');
            $table->foreign('pesanan_id')->references('id')->on('pesanans')->onDelete('cascade');
            $table->foreign('penerimaan_id')->references('id')->on('penerimaans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_serahterima');
    }
};
