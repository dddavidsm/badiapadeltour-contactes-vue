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
    Schema::create('pistas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('complejo_id')->constrained()->cascadeOnDelete(); // Relación con Complejo [cite: 249]
        $table->string('nombre'); // Identificador de pista [cite: 201]
        $table->enum('tipo', ['indoor', 'outdoor']); // Tipo de pista [cite: 201]
        $table->boolean('es_dobles')->default(true); // Individual o dobles [cite: 201]
        $table->decimal('precio_hora', 8, 2); // Precio
        $table->boolean('disponible')->default(true); // Estado [cite: 237]
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pistas');
    }
};
