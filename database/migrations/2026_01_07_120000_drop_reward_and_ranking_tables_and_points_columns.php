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
        // Quitar columnas de puntos en tablas existentes
        if (Schema::hasColumn('users', 'puntos')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('puntos');
            });
        }

        if (Schema::hasColumn('tournaments', 'premio_puntos')) {
            Schema::table('tournaments', function (Blueprint $table) {
                $table->dropColumn('premio_puntos');
            });
        }

        if (Schema::hasColumn('tournament_participants', 'puntos_ganados')) {
            Schema::table('tournament_participants', function (Blueprint $table) {
                $table->dropColumn('puntos_ganados');
            });
        }

        // Eliminar tablas de ranking y recompensas si existen
        Schema::dropIfExists('tournament_rankings');
        Schema::dropIfExists('rewards');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recrear tablas eliminadas con estructura básica
        if (!Schema::hasTable('rewards')) {
            Schema::create('rewards', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->text('descripcion')->nullable();
                $table->integer('cantidad')->default(0);
                $table->decimal('costo_puntos', 8, 2);
                $table->boolean('activa')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tournament_rankings')) {
            Schema::create('tournament_rankings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->decimal('puntos_totales', 10, 2)->default(0);
                $table->integer('torneos_jugados')->default(0);
                $table->integer('torneos_ganados')->default(0);
                $table->integer('posicion_global')->nullable();
                $table->timestamps();
            });
        }

        // Restaurar columnas de puntos si fueran necesarias
        if (!Schema::hasColumn('users', 'puntos')) {
            Schema::table('users', function (Blueprint $table) {
                $table->decimal('puntos', 10, 2)->default(0)->after('role');
            });
        }

        if (!Schema::hasColumn('tournaments', 'premio_puntos')) {
            Schema::table('tournaments', function (Blueprint $table) {
                $table->decimal('premio_puntos', 8, 2)->default(100)->after('participantes_actuales');
            });
        }

        if (!Schema::hasColumn('tournament_participants', 'puntos_ganados')) {
            Schema::table('tournament_participants', function (Blueprint $table) {
                $table->decimal('puntos_ganados', 8, 2)->default(0)->after('posicion');
            });
        }
    }
};
