<?php

namespace Database\Seeders;

use App\Models\Complejo;
use App\Models\Pista;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComplejoSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear complejos de ejemplo
        $complejos = [
            [
                'nombre' => 'Badia Del Vallés',
                'descripcion' => 'Centro de pádel de primer nivel con pistas de última generación',
                'direccion' => 'Av. de la Generalitat, 08214 Badia del Vallès, Barcelona',
                'imagen' => 'assets/Complejos/complejo_badia.png',
                'activo' => true,
            ],
            [
                'nombre' => 'Sabadell',
                'descripcion' => 'Instalaciones modernas con servicios complementarios',
                'direccion' => 'Carrer de la Indústria, 08205 Sabadell, Barcelona',
                'imagen' => 'assets/Complejos/complejo_sabadell.png',
                'activo' => true,
            ],
            [
                'nombre' => 'Cerdanyola Del Vallés',
                'descripcion' => 'Exclusivo club de pádel con atención personalizada',
                'direccion' => 'Av. de la Generalitat, 08290 Cerdanyola del Vallès, Barcelona',
                'imagen' => 'assets/Complejos/complejo_cerdanyoladelvalles.png',
                'activo' => true,
            ],
            [
                'nombre' => 'Castellar Del Vallés',
                'descripcion' => 'Moderno complejo de pádel con todas las comodidades',
                'direccion' => 'Carrer Principal, 08183 Castellar del Vallès, Barcelona',
                'imagen' => 'assets/Complejos/complejo_castellar.png',
                'activo' => true,
            ],
            [
                'nombre' => 'Castellbisbal',
                'descripcion' => 'Centro deportivo con pistas de última tecnología',
                'direccion' => 'Av. del Deporte, 08720 Castellbisbal, Barcelona',
                'imagen' => 'assets/Complejos/complejo_castellbisbal.png',
                'activo' => true,
            ],
            [
                'nombre' => 'Rubí',
                'descripcion' => 'Club de pádel con excelentes instalaciones',
                'direccion' => 'Carrer de l\'Esport, 08191 Rubí, Barcelona',
                'imagen' => 'assets/Complejos/complejo_rubi.png',
                'activo' => true,
            ],
            [
                'nombre' => 'Santa Perpètua De Mogoda',
                'descripcion' => 'Instalaciones deportivas modernas y accesibles',
                'direccion' => 'Av. del Pàdel, 08130 Santa Perpètua de Mogoda, Barcelona',
                'imagen' => 'assets/Complejos/complejo_staperpetuademogoda.png',
                'activo' => true,
            ],
            [
                'nombre' => 'Sant Quirze Del Vallés',
                'descripcion' => 'Club de pádel con servicios de primera categoría',
                'direccion' => 'Carrer de les Pistes, 08192 Sant Quirze del Vallès, Barcelona',
                'imagen' => 'assets/Complejos/complejo_santquirze.png',
                'activo' => true,
            ],
            [
                'nombre' => 'Barberà Del Vallés',
                'descripcion' => 'Complejo deportivo con modernas instalaciones de pádel',
                'direccion' => 'Av. de la Pau, 08210 Barberà del Vallès, Barcelona',
                'imagen' => 'assets/Complejos/complejo_barberadelvalles.png',
                'activo' => true,
            ],
        ];

        foreach ($complejos as $complejo) {
            Complejo::create($complejo);
        }

        // Crear pistas para cada complejo
        $pistas = [
            // Pistas de Badia Del Vallés (2 pistas)
            [
                'complejo_id' => 1,
                'nombre' => 'Pista 1 - Exterior',
                'tipo' => 'outdoor',
                'es_dobles' => true,
                'precio_hora' => 40.00,
                'imagen' => 'assets/Pistas/badiadelvalles_pista.jpg',
                'disponible' => true,
            ],
            [
                'complejo_id' => 1,
                'nombre' => 'Pista 2 - Interior',
                'tipo' => 'indoor',
                'es_dobles' => true,
                'precio_hora' => 45.00,
                'imagen' => 'assets/Pistas/badiadelvalles_pista2.webp',
                'disponible' => true,
            ],
            // Pistas de Sabadell (4 pistas)
            [
                'complejo_id' => 2,
                'nombre' => 'Pista 1 - Exterior',
                'tipo' => 'outdoor',
                'es_dobles' => true,
                'precio_hora' => 38.00,
                'imagen' => 'assets/Pistas/sabadell_pista.webp',
                'disponible' => true,
            ],
            [
                'complejo_id' => 2,
                'nombre' => 'Pista 2 - Interior',
                'tipo' => 'indoor',
                'es_dobles' => true,
                'precio_hora' => 42.00,
                'imagen' => 'assets/Pistas/sabadell_pista2.webp',
                'disponible' => true,
            ],
            [
                'complejo_id' => 2,
                'nombre' => 'Pista 3 - Exterior',
                'tipo' => 'outdoor',
                'es_dobles' => true,
                'precio_hora' => 38.00,
                'imagen' => 'assets/Pistas/sabadell_pista3.webp',
                'disponible' => true,
            ],
            [
                'complejo_id' => 2,
                'nombre' => 'Pista 4 - Interior',
                'tipo' => 'indoor',
                'es_dobles' => true,
                'precio_hora' => 44.00,
                'imagen' => 'assets/Pistas/sabadell_pista4.jpg',
                'disponible' => true,
            ],
            // Pistas de Cerdanyola Del Vallés (2 pistas)
            [
                'complejo_id' => 3,
                'nombre' => 'Pista 1 - Interior',
                'tipo' => 'indoor',
                'es_dobles' => true,
                'precio_hora' => 50.00,
                'imagen' => 'assets/Pistas/cerdanyoladelvalles_pista.jpg',
                'disponible' => true,
            ],
            [
                'complejo_id' => 3,
                'nombre' => 'Pista 2 - Exterior',
                'tipo' => 'outdoor',
                'es_dobles' => true,
                'precio_hora' => 45.00,
                'imagen' => 'assets/Pistas/cerdanyoladelvalles_pista2.jpg',
                'disponible' => true,
            ],
            // Pistas de Castellar Del Vallés (2 pistas)
            [
                'complejo_id' => 4,
                'nombre' => 'Pista 1 - Exterior',
                'tipo' => 'outdoor',
                'es_dobles' => true,
                'precio_hora' => 42.00,
                'imagen' => 'assets/Pistas/castellar_pista.webp',
                'disponible' => true,
            ],
            [
                'complejo_id' => 4,
                'nombre' => 'Pista 2 - Interior',
                'tipo' => 'indoor',
                'es_dobles' => true,
                'precio_hora' => 47.00,
                'imagen' => 'assets/Pistas/castellar_pista2.jfif',
                'disponible' => true,
            ],
            // Pistas de Castellbisbal (2 pistas)
            [
                'complejo_id' => 5,
                'nombre' => 'Pista 1 - Exterior',
                'tipo' => 'outdoor',
                'es_dobles' => true,
                'precio_hora' => 39.00,
                'imagen' => 'assets/Pistas/castellbisbal_pista.webp',
                'disponible' => true,
            ],
            [
                'complejo_id' => 5,
                'nombre' => 'Pista 2 - Interior',
                'tipo' => 'indoor',
                'es_dobles' => true,
                'precio_hora' => 43.00,
                'imagen' => 'assets/Pistas/castellbisbal_pista2.webp',
                'disponible' => true,
            ],
            // Pistas de Rubí (2 pistas)
            [
                'complejo_id' => 6,
                'nombre' => 'Pista 1 - Exterior',
                'tipo' => 'outdoor',
                'es_dobles' => true,
                'precio_hora' => 41.00,
                'imagen' => 'assets/Pistas/rubi_pista.webp',
                'disponible' => true,
            ],
            [
                'complejo_id' => 6,
                'nombre' => 'Pista 2 - Interior',
                'tipo' => 'indoor',
                'es_dobles' => true,
                'precio_hora' => 46.00,
                'imagen' => 'assets/Pistas/rubi_pista2.jpg',
                'disponible' => true,
            ],
            // Pistas de Santa Perpètua De Mogoda (2 pistas)
            [
                'complejo_id' => 7,
                'nombre' => 'Pista 1 - Exterior',
                'tipo' => 'outdoor',
                'es_dobles' => true,
                'precio_hora' => 40.00,
                'imagen' => 'assets/Pistas/staperpetuademogoda_pista.webp',
                'disponible' => true,
            ],
            [
                'complejo_id' => 7,
                'nombre' => 'Pista 2 - Interior',
                'tipo' => 'indoor',
                'es_dobles' => true,
                'precio_hora' => 44.00,
                'imagen' => 'assets/Pistas/staperpetuademogoda_pista2.webp',
                'disponible' => true,
            ],
            // Pistas de Sant Quirze Del Vallés (2 pistas)
            [
                'complejo_id' => 8,
                'nombre' => 'Pista 1 - Exterior',
                'tipo' => 'outdoor',
                'es_dobles' => true,
                'precio_hora' => 42.00,
                'imagen' => 'assets/Pistas/santquirze_pista.webp',
                'disponible' => true,
            ],
            [
                'complejo_id' => 8,
                'nombre' => 'Pista 2 - Interior',
                'tipo' => 'indoor',
                'es_dobles' => true,
                'precio_hora' => 48.00,
                'imagen' => 'assets/Pistas/santquirze_pista2.webp',
                'disponible' => true,
            ],
            // Pistas de Barberà Del Vallés (2 pistas)
            [
                'complejo_id' => 9,
                'nombre' => 'Pista 1 - Exterior',
                'tipo' => 'outdoor',
                'es_dobles' => true,
                'precio_hora' => 40.00,
                'imagen' => 'assets/Pistas/barberadelvalles_pista.jpg',
                'disponible' => true,
            ],
            [
                'complejo_id' => 9,
                'nombre' => 'Pista 2 - Interior',
                'tipo' => 'indoor',
                'es_dobles' => true,
                'precio_hora' => 45.00,
                'imagen' => 'assets/Pistas/barberadelvalles_pista2.jfif',
                'disponible' => true,
            ],
        ];

        foreach ($pistas as $pista) {
            Pista::create($pista);
        }
    }
}
