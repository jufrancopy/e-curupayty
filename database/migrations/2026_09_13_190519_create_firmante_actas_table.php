<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('firmante_actas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('cedula')->unique();
            $table->string('direccion');
            $table->string('ciudad');
            $table->string('instrumento');
            $table->text('sueno_musical');
            $table->longText('firma_digital'); // Canvas PNG Base64
            $table->string('codigo_verificacion')->unique();
            $table->string('estado')->default('pendiente_asamblea');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('firmante_actas');
    }
};
