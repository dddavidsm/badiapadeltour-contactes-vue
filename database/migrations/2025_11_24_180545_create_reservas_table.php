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
    Schema::create('reservas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Usuario [cite: 251]
        $table->foreignId('pista_id')->constrained()->cascadeOnDelete(); // Pista [cite: 250]
        $table->date('fecha_reserva'); // Fecha [cite: 204]
        $table->time('hora_inicio'); // Franja horaria [cite: 205]
        $table->time('hora_fin');
        $table->decimal('precio_total', 8, 2); // Precio total [cite: 215]
        $table->string('estado')->default('confirmada'); // Estado reserva [cite: 219]
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
