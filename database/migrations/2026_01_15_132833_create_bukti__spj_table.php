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
        Schema::create('spj_buktis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('spj_id')
                ->constrained('spjs')
                ->cascadeOnDelete();
            $table->string('file_path');       
            $table->string('file_name');        
            $table->string('file_type', 20);
            $table->string('jenis_bukti')->nullable();
            $table->text('keterangan')->nullable();    
            $table->foreignId('uploaded_by')    
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukti__spj');
    }
};
