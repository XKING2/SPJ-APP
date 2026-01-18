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
        Schema::table('kwitansis', function (Blueprint $table) {
            $table->foreignId('kwitansi_keg_id')
                ->nullable()
                ->after('id')
                ->constrained('kegiatan_kwitansis')
                ->cascadeOnDelete();
        });

        Schema::table('pemeriksaans', function (Blueprint $table) {
            $table->foreignId('id_pekerjaan')
                ->nullable()
                ->after('id')
                ->constrained('pekerjaans')
                ->cascadeOnDelete();
        });

        Schema::table('pekerjaans', function (Blueprint $table) {
            $table->foreignId('kegiatan_id')
                ->nullable()
                ->after('id')
                ->constrained('kegiatan_kwitansis')
                ->cascadeOnDelete();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
