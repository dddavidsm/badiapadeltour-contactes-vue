<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->text('descripcion')->nullable();
            $table->string('icono')->nullable();
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        Schema::table('productos', function (Blueprint $table) {
            $table->foreignId('categoria_id')->nullable()->after('id')->constrained('categorias')->onDelete('set null');
            $table->boolean('destacado')->default(false)->after('stock');
            $table->boolean('novedad')->default(false)->after('destacado');
            $table->integer('orden')->default(0)->after('novedad');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropColumn(['categoria_id', 'destacado', 'novedad', 'orden']);
        });
        Schema::dropIfExists('categorias');
    }
};
