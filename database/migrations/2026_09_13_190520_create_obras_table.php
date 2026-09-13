<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('obras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('titulo');
            $table->string('subtitulo')->nullable();
            $table->string('genero')->nullable();
            $table->string('duracion')->nullable();
            $table->integer('ano_creacion')->nullable();
            $table->string('plantilla_instrumental')->default('Ensamble de Cuerdas');
            $table->string('registro_dinapi')->nullable();
            $table->string('registro_apa')->nullable();
            $table->string('registro_aie')->nullable();
            $table->string('estado')->default('en_catalogo'); // borrador, braintrust, aprobada, en_catalogo
            $table->text('descripcion')->nullable();
            $table->string('audio_path')->nullable();
            $table->string('partitura_pdf_path')->nullable();
            $table->string('particelas_path')->nullable();
            $table->string('midi_path')->nullable();
            $table->decimal('precio_licencia', 10, 2)->default(0.00);
            $table->integer('reproducciones')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obras');
    }
};
