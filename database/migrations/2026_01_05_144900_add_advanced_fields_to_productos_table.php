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
        Schema::table('productos', function (Blueprint $table) {
            $table->enum('forma_pala', ['redonda', 'lagrima', 'diamante'])->nullable()->after('imagen');
            $table->enum('nivel', ['principiante', 'intermedio', 'avanzado', 'profesional'])->nullable()->after('forma_pala');
            $table->enum('genero', ['unisex', 'hombre', 'mujer', 'niño'])->nullable()->after('nivel');
            $table->enum('estilo', ['control', 'potencia', 'polivalente'])->nullable()->after('genero');
            $table->string('marca')->nullable()->after('estilo');
            $table->foreignId('jugador_id')->nullable()->after('marca')->constrained('jugadores')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['jugador_id']);
            $table->dropColumn(['forma_pala', 'nivel', 'genero', 'estilo', 'marca', 'jugador_id']);
        });
    }
};
