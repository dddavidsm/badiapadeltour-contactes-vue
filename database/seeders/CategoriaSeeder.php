<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tienda\Categoria;
use Illuminate\Support\Str;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Ropa',
                'slug' => 'ropa',
                'descripcion' => 'Ropa deportiva y accesorios textiles para pádel',
                'icono' => 'heroicon-o-user-circle',
                'orden' => 1,
            ],
            [
                'nombre' => 'Palas',
                'slug' => 'palas',
                'descripcion' => 'Palas de pádel profesionales y amateur',
                'icono' => 'heroicon-o-wrench',
                'orden' => 2,
            ],
            [
                'nombre' => 'Accesorios',
                'slug' => 'accesorios',
                'descripcion' => 'Accesorios y complementos para pádel',
                'icono' => 'heroicon-o-shopping-bag',
                'orden' => 3,
            ],
        ];

        foreach ($categorias as $categoria) {
            Categoria::updateOrCreate(
                ['slug' => $categoria['slug']],
                $categoria
            );
        }
    }
}
