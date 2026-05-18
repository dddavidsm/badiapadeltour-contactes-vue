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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('talla_pie')->nullable();
            $table->string('talla_camiseta', 10)->nullable();
            $table->string('talla_pantalon', 10)->nullable();
            $table->decimal('altura', 5, 2)->nullable();
            $table->decimal('peso', 5, 2)->nullable();
            $table->enum('nivel_juego', ['principiante', 'intermedio', 'avanzado', 'profesional'])->nullable();
            $table->enum('mano_dominante', ['diestra', 'zurda'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'talla_pie',
                'talla_camiseta',
                'talla_pantalon',
                'altura',
                'peso',
                'nivel_juego',
                'mano_dominante'
            ]);
        });
    }
};
