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

            $table->foreignId('spj_id')->nullable()
                ->constrained('spjs')->cascadeOnDelete();

            $table->string('no_surat')->nullable();
            $table->string('nama_pt');
            $table->string('alamat_pt');

            $table->date('tanggal_diterima')->nullable();
            $table->date('surat_dibuat')->nullable();

            $table->string('nomor_tlp_pt', 50)->nullable();

            $table->string('uang_terbilang')->nullable();
            $table->integer('jumlah_nominal')->nullable();
            $table->string('bulan_diterima')->nullable();
            $table->string('tahun_diterima')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
