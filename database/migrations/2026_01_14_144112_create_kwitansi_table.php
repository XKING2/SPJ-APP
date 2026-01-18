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

            $table->foreignId('spj_id')
                ->constrained('spjs')
                ->cascadeOnDelete();

            $table->string('no_rekening');
            $table->string('penerima_kwitansi');
            $table->string('telah_diterima_dari');
            $table->string('jabatan_penerima');

            $table->foreignId('id_pptk')->nullable()
                ->constrained('pptk')
                ->cascadeOnDelete();

            $table->foreignId('id_kegiatan')->nullable()
                ->constrained('kegiatan')
                ->cascadeOnDelete();

            $table->foreignId('id_plt')->nullable()
                ->constrained('plt')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kwitansis');
    }
};
