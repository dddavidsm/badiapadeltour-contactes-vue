<?php

namespace App\Http\Controllers\Bpt;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Models\Complejo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TournamentController extends Controller
{
    /**
     * Muestra la lista de torneos con filtros
     */
    public function index(Request $request)
    {
        $query = Tournament::with(['complejo', 'participantes']);

        // Filtro por complejo
        if ($request->filled('complejo')) {
            $query->where('complejo_id', $request->complejo);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por nivel
        if ($request->filled('nivel')) {
            $query->where('nivel', $request->nivel);
        }

        // Ordenar: próximos torneos primero, luego los recientes
        $torneos = $query->orderByRaw("CASE WHEN estado = 'Abierto' THEN 1 WHEN estado = 'En Curso' THEN 2 ELSE 3 END")
            ->orderBy('fecha_inicio', 'desc')
            ->paginate(12);

        // Obtener complejos para el filtro
        $complejos = Complejo::orderBy('nombre')->get();

        return view('torneos.index', compact('torneos', 'complejos'));
    }

    /**
     * Muestra el detalle de un torneo
     */
    public function show($id)
    {
        $torneo = Tournament::with(['complejo', 'participantes.user'])->findOrFail($id);
        
        $usuarioInscrito = false;
        if (Auth::check()) {
            $usuarioInscrito = $torneo->usuarioInscrito(Auth::id());
        }

        return view('torneos.show', compact('torneo', 'usuarioInscrito'));
    }

    /**
     * Inscribe a un usuario en un torneo
     */
    public function inscribir(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para inscribirte');
        }

        $torneo = Tournament::findOrFail($id);

        // Verificar si el torneo está abierto
        if (!$torneo->estaAbierto()) {
            return back()->with('error', 'El torneo no está disponible para inscripciones');
        }

        // Verificar si ya está inscrito
        if ($torneo->usuarioInscrito(Auth::id())) {
            return back()->with('error', 'Ya estás inscrito en este torneo');
        }

        try {
            DB::beginTransaction();

            // Crear participante
            TournamentParticipant::create([
                'tournament_id' => $torneo->id,
                'user_id' => Auth::id(),
                'estado' => 'Inscrito',
            ]);

            // Incrementar contador de participantes
            $torneo->incrementarParticipantes();

            DB::commit();

            return back()->with('success', '¡Inscripción exitosa! Te esperamos en el torneo.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar la inscripción');
        }
    }

    /**
     * Muestra los torneos del usuario
     */
    public function misTorneos()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $participaciones = TournamentParticipant::with(['tournament.complejo'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('torneos.mis-torneos', compact('participaciones'));
    }
}
