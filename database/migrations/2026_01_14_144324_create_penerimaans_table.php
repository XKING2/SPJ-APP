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

            $table->foreignId('pesanan_id')->nullable()
                ->constrained('pesanans')->cascadeOnDelete();

            $table->foreignId('spj_id')->nullable()
                ->constrained('spjs')->cascadeOnDelete();

            $table->date('surat_dibuat')->nullable();
            $table->string('no_surat');

            $table->string('nama_pihak_kedua');
            $table->string('jabatan_pihak_kedua');

            $table->integer('subtotal');
            $table->integer('ppn')->nullable();
            $table->integer('grandtotal');
            $table->integer('dibulatkan');
            $table->string('terbilang');

            $table->foreignId('id_serahbarang')->nullable()
                ->constrained('serah_barang')->cascadeOnDelete();

            $table->integer('pph')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerimaans');
    }
};
