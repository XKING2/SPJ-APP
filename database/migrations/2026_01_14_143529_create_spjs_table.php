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
            

            $table->enum('status', ['draft', 'diajukan', 'valid', 'belum_valid'])->default('draft');
            $table->foreignId('user_id')->nullable()
                ->constrained('users')->cascadeOnDelete();

            $table->enum('status2', ['draft', 'diajukan', 'valid', 'belum_valid'])->default('draft');
            $table->enum('types', ['GU', 'LS','PO'])->nullable();

            $table->boolean('notified')->default(false);
            $table->boolean('notified_bendahara')->default(false);
            $table->boolean('notified_kasubag')->default(false);
            $table->boolean('notifiedby_kasubag')->default(false);
            $table->string('tahun')->nullable();

            $table->timestamps();

            
        });

        Schema::create('spj_feedbacks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('spj_id')
                    ->constrained('spjs');

                $table->string('section', 50);
                $table->integer('record_id')->nullable();
                $table->string('field', 100);
                $table->text('message');

                $table->enum('role', ['Bendahara', 'Kasubag'])->default('Bendahara');

                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spjs');
        Schema::dropIfExists('spj_feedbacks');
    }
};
