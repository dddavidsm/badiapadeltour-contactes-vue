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
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->foreignId('complejo_id')->constrained('complejos')->onDelete('cascade');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('nivel', ['Principiante', 'Intermedio', 'Avanzado', 'Profesional'])->default('Intermedio');
            $table->enum('estado', ['Abierto', 'En Curso', 'Finalizado', 'Cancelado'])->default('Abierto');
            $table->integer('max_participantes')->default(32);
            $table->integer('participantes_actuales')->default(0);
            $table->string('imagen')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};
