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
        Schema::create('kwitansis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('spj_id');
            $table->string('no_rekening');
            $table->string('no_rekening_tujuan');
            $table->string('nama_bank');
            $table->string('penerima_kwitansi');
            $table->string('sub_kegiatan');
            $table->string('telah_diterima_dari');
            $table->bigInteger('jumlah_nominal');
            $table->string('uang_terbilang');
            $table->string('jabatan_penerima');
            $table->string('npwp');
            $table->string('nama_pt');
            $table->text('pembayaran');
            $table->timestamps();
            $table->foreign('spj_id')->references('id')->on('spjs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_kwitansi');
    }
};
