<?php

namespace Database\Seeders;

use App\Models\Reserva;
use App\Models\User;
use App\Models\Pista;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ReservasSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener usuarios no admin (excluyendo al admin)
        $usuarios = User::where('role', 'user')->get();
        
        // Obtener todas las pistas
        $pistas = Pista::all();

        if ($usuarios->isEmpty() || $pistas->isEmpty()) {
            $this->command->warn('No hay usuarios o pistas disponibles para crear reservas.');
            return;
        }

        // Crear reservas para diferentes fechas y horas
        $reservas = [
            // Reservas pasadas (completadas)
            [
                'user_id' => $usuarios->random()->id,
                'pista_id' => $pistas->random()->id,
                'fecha_reserva' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'hora_inicio' => '09:00:00',
                'hora_fin' => '10:30:00',
                'precio_total' => 35.00,
                'estado' => 'completada',
            ],
            [
                'user_id' => $usuarios->random()->id,
                'pista_id' => $pistas->random()->id,
                'fecha_reserva' => Carbon::now()->subDays(8)->format('Y-m-d'),
                'hora_inicio' => '11:00:00',
                'hora_fin' => '12:30:00',
                'precio_total' => 40.00,
                'estado' => 'completada',
            ],
            [
                'user_id' => $usuarios->random()->id,
                'pista_id' => $pistas->random()->id,
                'fecha_reserva' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'hora_inicio' => '16:00:00',
                'hora_fin' => '17:30:00',
                'precio_total' => 45.00,
                'estado' => 'completada',
            ],
            [
                'user_id' => $usuarios->random()->id,
                'pista_id' => $pistas->random()->id,
                'fecha_reserva' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'hora_inicio' => '18:00:00',
                'hora_fin' => '19:30:00',
                'precio_total' => 50.00,
                'estado' => 'completada',
            ],
            
            // Reservas futuras (confirmadas)
            [
                'user_id' => $usuarios->random()->id,
                'pista_id' => $pistas->random()->id,
                'fecha_reserva' => Carbon::now()->addDays(2)->format('Y-m-d'),
                'hora_inicio' => '10:00:00',
                'hora_fin' => '11:30:00',
                'precio_total' => 40.00,
                'estado' => 'confirmada',
            ],
            [
                'user_id' => $usuarios->random()->id,
                'pista_id' => $pistas->random()->id,
                'fecha_reserva' => Carbon::now()->addDays(3)->format('Y-m-d'),
                'hora_inicio' => '12:00:00',
                'hora_fin' => '13:30:00',
                'precio_total' => 42.00,
                'estado' => 'confirmada',
            ],
            [
                'user_id' => $usuarios->random()->id,
                'pista_id' => $pistas->random()->id,
                'fecha_reserva' => Carbon::now()->addDays(4)->format('Y-m-d'),
                'hora_inicio' => '15:00:00',
                'hora_fin' => '16:30:00',
                'precio_total' => 45.00,
                'estado' => 'confirmada',
            ],
            [
                'user_id' => $usuarios->random()->id,
                'pista_id' => $pistas->random()->id,
                'fecha_reserva' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'hora_inicio' => '17:00:00',
                'hora_fin' => '18:30:00',
                'precio_total' => 48.00,
                'estado' => 'confirmada',
            ],
            [
                'user_id' => $usuarios->random()->id,
                'pista_id' => $pistas->random()->id,
                'fecha_reserva' => Carbon::now()->addDays(6)->format('Y-m-d'),
                'hora_inicio' => '19:00:00',
                'hora_fin' => '20:30:00',
                'precio_total' => 50.00,
                'estado' => 'confirmada',
            ],
            [
                'user_id' => $usuarios->random()->id,
                'pista_id' => $pistas->random()->id,
                'fecha_reserva' => Carbon::now()->addDays(7)->format('Y-m-d'),
                'hora_inicio' => '09:00:00',
                'hora_fin' => '10:30:00',
                'precio_total' => 38.00,
                'estado' => 'confirmada',
            ],
            [
                'user_id' => $usuarios->random()->id,
                'pista_id' => $pistas->random()->id,
                'fecha_reserva' => Carbon::now()->addDays(8)->format('Y-m-d'),
                'hora_inicio' => '11:00:00',
                'hora_fin' => '12:30:00',
                'precio_total' => 40.00,
                'estado' => 'confirmada',
            ],
            [
                'user_id' => $usuarios->random()->id,
                'pista_id' => $pistas->random()->id,
                'fecha_reserva' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'hora_inicio' => '14:00:00',
                'hora_fin' => '15:30:00',
                'precio_total' => 43.00,
                'estado' => 'confirmada',
            ],
            [
                'user_id' => $usuarios->random()->id,
                'pista_id' => $pistas->random()->id,
                'fecha_reserva' => Carbon::now()->addDays(12)->format('Y-m-d'),
                'hora_inicio' => '16:00:00',
                'hora_fin' => '17:30:00',
                'precio_total' => 45.00,
                'estado' => 'confirmada',
            ],
            [
                'user_id' => $usuarios->random()->id,
                'pista_id' => $pistas->random()->id,
                'fecha_reserva' => Carbon::now()->addDays(14)->format('Y-m-d'),
                'hora_inicio' => '18:00:00',
                'hora_fin' => '19:30:00',
                'precio_total' => 47.00,
                'estado' => 'confirmada',
            ],
            [
                'user_id' => $usuarios->random()->id,
                'pista_id' => $pistas->random()->id,
                'fecha_reserva' => Carbon::now()->addDays(15)->format('Y-m-d'),
                'hora_inicio' => '20:00:00',
                'hora_fin' => '21:30:00',
                'precio_total' => 52.00,
                'estado' => 'confirmada',
            ],
            
            // Algunas reservas canceladas
            [
                'user_id' => $usuarios->random()->id,
                'pista_id' => $pistas->random()->id,
                'fecha_reserva' => Carbon::now()->addDays(9)->format('Y-m-d'),
                'hora_inicio' => '13:00:00',
                'hora_fin' => '14:30:00',
                'precio_total' => 42.00,
                'estado' => 'cancelada',
            ],
            [
                'user_id' => $usuarios->random()->id,
                'pista_id' => $pistas->random()->id,
                'fecha_reserva' => Carbon::now()->addDays(11)->format('Y-m-d'),
                'hora_inicio' => '10:00:00',
                'hora_fin' => '11:30:00',
                'precio_total' => 40.00,
                'estado' => 'cancelada',
            ],
        ];

        foreach ($reservas as $reserva) {
            Reserva::create($reserva);
        }

        $this->command->info('✓ Creadas ' . count($reservas) . ' reservas de ejemplo');
    }
}
