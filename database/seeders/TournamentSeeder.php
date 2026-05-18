<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Models\User;
use App\Models\Complejo;
use Carbon\Carbon;

class TournamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $complejos = Complejo::all();
        
        if ($complejos->isEmpty()) {
            $this->command->warn('No hay complejos en la base de datos. Ejecuta el seeder de complejos primero.');
            return;
        }

        // Crear 5 torneos pasados (finalizados)
        $torneosFinalizados = [
            [
                'nombre' => 'Copa Badia Verano 2025',
                'descripcion' => 'Primer torneo oficial de la temporada de verano. Competencia intensa entre los mejores jugadores de la zona.',
                'complejo_id' => $complejos->random()->id,
                'fecha_inicio' => Carbon::now()->subMonths(6),
                'fecha_fin' => Carbon::now()->subMonths(6)->addDays(2),
                'nivel' => 'Avanzado',
                'estado' => 'Finalizado',
                'max_participantes' => 32,
                'participantes_actuales' => 32,
            ],
            [
                'nombre' => 'Torneo Principiantes Otoño',
                'descripcion' => 'Torneo especial para jugadores que están comenzando en el pádel. Ambiente amigable y competitivo.',
                'complejo_id' => $complejos->random()->id,
                'fecha_inicio' => Carbon::now()->subMonths(4),
                'fecha_fin' => Carbon::now()->subMonths(4)->addDay(),
                'nivel' => 'Principiante',
                'estado' => 'Finalizado',
                'max_participantes' => 24,
                'participantes_actuales' => 20,
            ],
            [
                'nombre' => 'Gran Slam BPT Navidad',
                'descripcion' => 'El torneo más esperado del año. Premios especiales y puntos dobles para el ranking.',
                'complejo_id' => $complejos->random()->id,
                'fecha_inicio' => Carbon::now()->subMonths(1),
                'fecha_fin' => Carbon::now()->subMonths(1)->addDays(3),
                'nivel' => 'Profesional',
                'estado' => 'Finalizado',
                'max_participantes' => 48,
                'participantes_actuales' => 48,
            ],
            [
                'nombre' => 'Torneo Relámpago Intermedio',
                'descripcion' => 'Competencia rápida de un solo día. Formato eliminación directa.',
                'complejo_id' => $complejos->random()->id,
                'fecha_inicio' => Carbon::now()->subWeeks(3),
                'fecha_fin' => Carbon::now()->subWeeks(3),
                'nivel' => 'Intermedio',
                'estado' => 'Finalizado',
                'max_participantes' => 16,
                'participantes_actuales' => 16,
            ],
            [
                'nombre' => 'Copa Año Nuevo 2026',
                'descripcion' => 'Comienza el año con la mejor competencia de pádel. ¡Demuestra tu nivel!',
                'complejo_id' => $complejos->random()->id,
                'fecha_inicio' => Carbon::now()->subWeeks(1),
                'fecha_fin' => Carbon::now()->subWeeks(1)->addDay(),
                'nivel' => 'Avanzado',
                'estado' => 'Finalizado',
                'max_participantes' => 32,
                'participantes_actuales' => 28,
            ],
        ];

        foreach ($torneosFinalizados as $torneoData) {
            $torneo = Tournament::create($torneoData);
            $this->asignarParticipantes($torneo, $torneoData['participantes_actuales'], true);
        }

        // Crear 3 torneos futuros (abiertos)
        $torneosAbiertos = [
            [
                'nombre' => 'Torneo San Valentín 2026',
                'descripcion' => 'Torneo especial para parejas. Celebra el amor por el pádel con tu compañero/a ideal.',
                'complejo_id' => $complejos->random()->id,
                'fecha_inicio' => Carbon::now()->addWeeks(2),
                'fecha_fin' => Carbon::now()->addWeeks(2)->addDays(2),
                'nivel' => 'Intermedio',
                'estado' => 'Abierto',
                'max_participantes' => 32,
                'participantes_actuales' => 12,
            ],
            [
                'nombre' => 'Copa BPT Primavera',
                'descripcion' => 'Inaugura la temporada de primavera con este emocionante torneo. Inscripciones abiertas.',
                'complejo_id' => $complejos->random()->id,
                'fecha_inicio' => Carbon::now()->addMonth(),
                'fecha_fin' => Carbon::now()->addMonth()->addDays(3),
                'nivel' => 'Avanzado',
                'estado' => 'Abierto',
                'max_participantes' => 48,
                'participantes_actuales' => 8,
            ],
            [
                'nombre' => 'Torneo Express Principiantes',
                'descripcion' => 'Perfecto para debutantes. Un día de diversión y aprendizaje garantizado.',
                'complejo_id' => $complejos->random()->id,
                'fecha_inicio' => Carbon::now()->addWeeks(3),
                'fecha_fin' => Carbon::now()->addWeeks(3),
                'nivel' => 'Principiante',
                'estado' => 'Abierto',
                'max_participantes' => 24,
                'participantes_actuales' => 5,
            ],
        ];

        foreach ($torneosAbiertos as $torneoData) {
            $torneo = Tournament::create($torneoData);
            $this->asignarParticipantes($torneo, $torneoData['participantes_actuales'], false);
        }

        $this->command->info('✓ Torneos creados exitosamente');
    }

    /**
     * Asigna participantes aleatorios a un torneo
     */
    private function asignarParticipantes(Tournament $torneo, int $cantidad, bool $finalizado = false)
    {
        $usuarios = User::where('role', '!=', 'admin')
            ->inRandomOrder()
            ->limit($cantidad)
            ->get();

        foreach ($usuarios as $index => $usuario) {
            $posicion = null;
            $estado = 'Inscrito';

            if ($finalizado) {
                $posicion = $index + 1;
                $estado = $posicion === 1 ? 'Ganador' : 'Eliminado';
            } else {
                $estado = 'Inscrito';
            }

            TournamentParticipant::create([
                'tournament_id' => $torneo->id,
                'user_id' => $usuario->id,
                'posicion' => $posicion,
                'estado' => $estado,
            ]);
        }
    }
}
