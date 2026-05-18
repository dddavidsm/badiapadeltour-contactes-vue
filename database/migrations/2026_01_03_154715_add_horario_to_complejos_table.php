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
        Schema::table('complejos', function (Blueprint $table) {
            $table->time('hora_apertura')->default('08:00')->after('direccion');
            $table->time('hora_cierre')->default('23:00')->after('hora_apertura');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complejos', function (Blueprint $table) {
            $table->dropColumn(['hora_apertura', 'hora_cierre']);
        });
    }
};
