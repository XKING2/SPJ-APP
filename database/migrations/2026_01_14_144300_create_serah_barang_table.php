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
        Schema::create('serah_barang', function (Blueprint $table) {
            $table->id();

            $table->foreignId('spj_id')->nullable()
                ->constrained('spjs')->cascadeOnDelete();

            $table->foreignId('id_plt')->nullable()
                ->constrained('plt')->cascadeOnDelete();

            $table->foreignId('id_pihak_kedua')->nullable()
                ->constrained('pihak_kedua')->cascadeOnDelete();

            $table->foreignId('id_pemeriksaan')->nullable()
                ->constrained('pemeriksaans')->cascadeOnDelete();

            $table->string('no_suratsss')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serah_barang');
    }
};
