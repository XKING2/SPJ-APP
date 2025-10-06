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
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('spj_id');
            $table->string('no_surat');
            $table->string('nama_pt');
            $table->string('alamat_pt');
            $table->date('tanggal_diterima')->nullable();
            $table->date('surat_dibuat')->nullable();
            $table->string('nomor_tlp_pt');
            $table->timestamps();
            $table->foreign('spj_id')->references('id')->on('spjs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_pesanan');
    }
};
