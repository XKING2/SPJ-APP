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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('NIP')->unique();
            $table->string('password');
            $table->string('jabatan');
            $table->string('Alamat');
            $table->integer('nomor_tlp');
            $table->enum('role', ['superadmin', 'admin','user'])->default('user');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('NIP')->unique();
            $table->string('password');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('NIP')->unique();
            $table->string('password');
            $table->string('jabatan');
            $table->string('Alamat');
            $table->integer('nomor_tlp');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
