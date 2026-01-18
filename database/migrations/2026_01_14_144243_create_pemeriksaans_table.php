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

            $table->foreignId('spj_id')->nullable()
                ->constrained('spjs')->nullOnDelete();

            $table->foreignId('pesanan_id')->nullable()
                ->constrained('pesanans')->cascadeOnDelete();

            $table->string('tanggals_diterima');
            $table->string('hari_diterima');
            $table->string('bulan_diterima');
            $table->string('tahun_diterima');

            $table->string('nama_pihak_kedua');
            $table->string('jabatan_pihak_kedua');
            $table->string('alamat_pihak_kedua');

            $table->string('no_suratssss')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaans');
    }
};
