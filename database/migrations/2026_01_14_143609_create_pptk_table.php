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
        Schema::create('pptk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pptk');
            $table->string('idinjab_pptk');
            $table->string('nip_pptk', 60);
            $table->string('gol_pptk')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pptk');
    }
};
