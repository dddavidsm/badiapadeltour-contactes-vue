<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pista;
use App\Models\Reserva;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GuestCheckoutController extends Controller
{
    public function checkAvailability(Request $request): JsonResponse
    {
        $data = $request->validate([
            'pista_id' => ['required', 'exists:pistas,id'],
            'fecha_reserva' => ['required', 'date', 'after_or_equal:today'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'duracion_minutos' => ['required', 'integer', 'in:60,90,120'],
        ]);

        $startTime = Carbon::createFromFormat('H:i', $data['hora_inicio']);
        $endTime = (clone $startTime)->addMinutes((int) $data['duracion_minutos']);

        $isOccupied = Reserva::query()
            ->where('pista_id', $data['pista_id'])
            ->where('fecha_reserva', $data['fecha_reserva'])
            ->where('estado', '!=', 'cancelada')
            ->where('hora_inicio', '<', $endTime->format('H:i:s'))
            ->where('hora_fin', '>', $startTime->format('H:i:s'))
            ->exists();

        return response()->json([
            'available' => ! $isOccupied,
            'hora_fin' => $endTime->format('H:i'),
        ]);
    }

    public function checkout(Request $request): JsonResponse
    {
        $data = $request->validate([
            'guest' => ['required', 'array'],
            'guest.name' => ['required', 'string', 'max:255'],
            'guest.email' => ['required', 'email', 'max:255'],
            'guest.phone' => ['nullable', 'string', 'max:25'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.pista_id' => ['required', 'exists:pistas,id'],
            'items.*.fecha_reserva' => ['required', 'date', 'after_or_equal:today'],
            'items.*.hora_inicio' => ['required', 'date_format:H:i'],
            'items.*.duracion_minutos' => ['required', 'integer', 'in:60,90,120'],
            'items.*.notas' => ['nullable', 'string', 'max:500'],
        ]);

        $guestUser = User::firstOrCreate(
            ['email' => strtolower($data['guest']['email'])],
            [
                'name' => $data['guest']['name'],
                'password' => Hash::make(Str::password(16)),
                'role' => 'user',
                'telefono' => $data['guest']['phone'] ?? null,
            ]
        );

        $result = DB::transaction(function () use ($data, $guestUser) {
            $reservas = [];
            $total = 0;

            foreach ($data['items'] as $item) {
                $pista = Pista::findOrFail((int) $item['pista_id']);
                $startTime = Carbon::createFromFormat('H:i', $item['hora_inicio']);
                $duration = (int) $item['duracion_minutos'];
                $endTime = (clone $startTime)->addMinutes($duration);

                $hasConflict = Reserva::query()
                    ->where('pista_id', $pista->id)
                    ->where('fecha_reserva', $item['fecha_reserva'])
                    ->where('estado', '!=', 'cancelada')
                    ->where('hora_inicio', '<', $endTime->format('H:i:s'))
                    ->where('hora_fin', '>', $startTime->format('H:i:s'))
                    ->exists();

                if ($hasConflict) {
                    return response()->json([
                        'message' => 'Una de las pistas ya no esta disponible en esa franja.',
                        'conflict_item' => [
                            'pista_id' => $pista->id,
                            'nombre' => $pista->nombre,
                            'fecha_reserva' => $item['fecha_reserva'],
                            'hora_inicio' => $item['hora_inicio'],
                        ],
                    ], 409);
                }

                $price = round(((float) $pista->precio_hora) * ($duration / 60), 2);

                $reserva = Reserva::create([
                    'user_id' => $guestUser->id,
                    'pista_id' => $pista->id,
                    'fecha_reserva' => $item['fecha_reserva'],
                    'hora_inicio' => $startTime->format('H:i:s'),
                    'hora_fin' => $endTime->format('H:i:s'),
                    'precio_total' => $price,
                    'estado' => 'confirmada',
                ]);

                $total += $price;
                $reservas[] = $reserva->load('pista.complejo');
            }

            return [
                'order_reference' => 'BPT-' . strtoupper(Str::random(8)),
                'guest' => [
                    'name' => $guestUser->name,
                    'email' => $guestUser->email,
                    'phone' => $data['guest']['phone'] ?? null,
                ],
                'total' => round($total, 2),
                'reservas' => $reservas,
            ];
        });

        if ($result instanceof JsonResponse) {
            return $result;
        }

        return response()->json($result, 201);
    }
}
