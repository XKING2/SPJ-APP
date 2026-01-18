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
        Schema::create('plt', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pihak_pertama');
            $table->string('jabatan_pihak_pertama');
            $table->string('nip_pihak_pertama');
            $table->string('gol_pihak_pertama');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plt');
    }
};
