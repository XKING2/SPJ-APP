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
        Schema::create('spjs', function (Blueprint $table) {
            $table->id();

            // Relasi utama ke tabel lain
            $table->unsignedBigInteger('pesanan_id')->nullable();
            $table->unsignedBigInteger('pemeriksaan_id')->nullable();
            $table->unsignedBigInteger('penerimaan_id')->nullable();
            $table->unsignedBigInteger('kwitansi_id')->nullable();

            // Informasi dokumen SPJ
            $table->string('nomor_spj')->nullable();
            $table->date('tanggal_spj')->nullable();
            $table->string('status')->default('draft'); // draft / final / approved
            $table->string('file_path')->nullable(); // jika hasil word disimpan

            // Snapshot: data penting dari tabel lain saat SPJ dibuat
            $table->string('nama_pt_snapshot')->nullable();
            $table->string('nama_pemesan_snapshot')->nullable();
            $table->string('pihak_kedua_snapshot')->nullable();
            $table->string('jabatan_pihak_kedua_snapshot')->nullable();
            $table->decimal('total_snapshot', 15, 2)->nullable();
            $table->text('hasil_pemeriksaan_snapshot')->nullable();
            $table->text('pembayaran_snapshot')->nullable();
            $table->string('terbilang_snapshot')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('pesanan_id')->references('id')->on('pesanans')->onDelete('set null');
            $table->foreign('pemeriksaan_id')->references('id')->on('pemeriksaans')->onDelete('set null');
            $table->foreign('penerimaan_id')->references('id')->on('penerimaans')->onDelete('set null');
            $table->foreign('kwitansi_id')->references('id')->on('kwitansis')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spjs');
    }
};
