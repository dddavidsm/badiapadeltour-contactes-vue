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
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('costo_puntos', 8, 2);
            $table->enum('tipo', ['Descuento', 'Producto', 'Entrada Gratis'])->default('Descuento');
            $table->decimal('valor_descuento', 8, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->boolean('activo')->default(true);
            $table->string('imagen')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};
