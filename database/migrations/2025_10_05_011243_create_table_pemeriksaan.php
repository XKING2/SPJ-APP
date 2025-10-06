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
        Schema::create('pemeriksaans', function (Blueprint $table) {
            $table->id();
            $table->string('hari_diterima');
            $table->unsignedBigInteger('spj_id');
            $table->unsignedBigInteger('pesanan_id');
            $table->string('tanggal_diterima');
            $table->string('bulan_diterima');
            $table->string('tahun_diterima');
            $table->string('nama_pihak_kedua');
            $table->string('jabatan_pihak_kedua');
            $table->string('alamat_pihak_kedua');
            $table->string('pekerjaan');
            $table->timestamps();
            $table->foreign('pesanan_id')->references('id')->on('pesanans')->onDelete('cascade');
            $table->foreign('spj_id')->references('id')->on('spjs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_pemeriksaan');
    }
};
