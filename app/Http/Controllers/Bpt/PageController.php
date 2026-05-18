<?php

namespace App\Http\Controllers\Bpt;

use App\Http\Controllers\Controller;
use App\Models\Complejo;
use App\Models\Pista;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function home()
    {
        $complejos = Complejo::with('pistas')->get();
        
        // Estadísticas dinámicas desde la base de datos
        $stats = [
            'pistas' => \App\Models\Pista::count(),
            'usuarios' => \App\Models\User::count(),
            'complejos' => Complejo::count(),
            'reservas' => Reserva::count(),
        ];
        
        return view('welcome', compact('complejos', 'stats'));
    }

    public function tienda()
    {
        $categorias = \App\Models\Tienda\Categoria::withCount('productos')->orderBy('orden')->get();
        $destacados = \App\Models\Tienda\Producto::where('destacado', true)
            ->with('categoria')
            ->take(8)
            ->get();
        $novedades = \App\Models\Tienda\Producto::where('novedad', true)
            ->with('categoria')
            ->take(4)
            ->get();
        
        return view('tienda.index', compact('categorias', 'destacados', 'novedades'));
    }

    public function complejos(Request $request)
    {
        // Construir query con filtros
        $query = Complejo::withCount('pistas');
        
        // Filtro de búsqueda por nombre o dirección
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', '%' . $buscar . '%')
                  ->orWhere('direccion', 'like', '%' . $buscar . '%');
            });
        }
        
        $complejos = $query->get();
        
        return view('complejos.index', compact('complejos'));
    }

    public function pistas(Request $request)
    {
        $complejos = Complejo::all();
        
        // Construir query con filtros
        $query = Pista::with('complejo');
        
        // Filtro por complejo
        if ($request->filled('complejo_id')) {
            $query->where('complejo_id', $request->complejo_id);
        }
        
        // Filtro por tipo (indoor/outdoor)
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        
        // Filtro por precio máximo
        if ($request->filled('precio_max')) {
            $query->where('precio_hora', '<=', $request->precio_max);
        }
        
        // Filtro por apta para dobles
        if ($request->filled('dobles') && $request->dobles == '1') {
            $query->where('es_dobles', true);
        }
        
        $pistas = $query->get();
        
        // Obtener complejo específico si se está filtrando
        $complejo = $request->filled('complejo_id') 
            ? Complejo::find($request->complejo_id) 
            : null;
        
        return view('pistas.index', compact('pistas', 'complejo', 'complejos'));
    }

    public function pistaDetalle($id)
    {
        $pista = Pista::with('complejo')->findOrFail($id);
        
        // Imágenes de ejemplo para la pista
        $images = [
            'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=800', // Pista de pádel
            'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=800', // Otra pista
            'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=800', // Más pistas
        ];
        
        return view('pistas.detalle', compact('pista', 'images'));
    }

    public function reservarFormulario($pistaId)
    {
        $pista = Pista::with('complejo')->findOrFail($pistaId);
        
        // Obtener horario de disponibilidad del complejo
        $complejo = $pista->complejo;
        $horariosDisponibles = [];
        
        if ($complejo->hora_apertura && $complejo->hora_cierre) {
            $horaInicio = intval(substr($complejo->hora_apertura, 0, 2));
            $horaFin = intval(substr($complejo->hora_cierre, 0, 2));
            
            for ($hora = $horaInicio; $hora < $horaFin; $hora++) {
                $horariosDisponibles[] = str_pad($hora, 2, '0', STR_PAD_LEFT) . ':00';
            }
        } else {
            // Horarios por defecto si no están configurados (8 AM a 10 PM)
            for ($hora = 8; $hora < 22; $hora++) {
                $horariosDisponibles[] = str_pad($hora, 2, '0', STR_PAD_LEFT) . ':00';
            }
        }
        
        // Obtener próximos 30 días
        $diasDisponibles = [];
        for ($i = 0; $i < 30; $i++) {
            $fecha = now()->addDays($i)->format('Y-m-d');
            $diasDisponibles[] = $fecha;
        }
        
        return view('reservas.formulario', compact('pista', 'horariosDisponibles', 'diasDisponibles'));
    }

    public function guardarReserva(Request $request, $pistaId)
    {
        $pista = Pista::findOrFail($pistaId);
        
        // Validar campos
        $validated = $request->validate([
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i',
        ], [
            'fecha.required' => 'La fecha es requerida.',
            'fecha.date' => 'La fecha debe ser válida.',
            'fecha.after_or_equal' => 'La fecha debe ser hoy o una fecha futura.',
            'hora_inicio.required' => 'La hora de inicio es requerida.',
            'hora_inicio.date_format' => 'La hora de inicio debe estar en formato HH:mm.',
            'hora_fin.required' => 'La hora de fin es requerida.',
            'hora_fin.date_format' => 'La hora de fin debe estar en formato HH:mm.',
        ]);

        // Validar que la hora de fin sea después de la hora de inicio
        $horaInicioMinutos = intval(substr($validated['hora_inicio'], 0, 2)) * 60 + intval(substr($validated['hora_inicio'], 3, 2));
        $horaFinMinutos = intval(substr($validated['hora_fin'], 0, 2)) * 60 + intval(substr($validated['hora_fin'], 3, 2));
        
        if ($horaFinMinutos <= $horaInicioMinutos) {
            return back()->withErrors([
                'hora_fin' => 'La hora de fin debe ser posterior a la hora de inicio.'
            ])->withInput();
        }

        // Verificar disponibilidad: buscar conflictos con reservas existentes
        $reservasExistentes = Reserva::where('pista_id', $pistaId)
            ->where('fecha_reserva', $validated['fecha'])
            ->where('estado', '!=', 'cancelada')
            ->get();

        foreach ($reservasExistentes as $reserva) {
            $reservaInicioMinutos = intval(substr($reserva->hora_inicio, 0, 2)) * 60 + intval(substr($reserva->hora_inicio, 3, 2));
            $reservaFinMinutos = intval(substr($reserva->hora_fin, 0, 2)) * 60 + intval(substr($reserva->hora_fin, 3, 2));
            
            // Verificar solapamiento: si hay solapamiento, rechazar
            if ($horaInicioMinutos < $reservaFinMinutos && $horaFinMinutos > $reservaInicioMinutos) {
                return back()->withErrors([
                    'horario' => 'El horario seleccionado no está disponible. Por favor, elige otro horario.'
                ])->withInput();
            }
        }

        // Calcular precio total basado en las horas
        $horas = ($horaFinMinutos - $horaInicioMinutos) / 60;
        $precioTotal = $horas * $pista->precio_hora;

        $reserva = Reserva::create([
            'user_id' => Auth::id(),
            'pista_id' => $pistaId,
            'fecha_reserva' => $validated['fecha'],
            'hora_inicio' => $validated['hora_inicio'],
            'hora_fin' => $validated['hora_fin'],
            'precio_total' => $precioTotal,
            'estado' => 'confirmada',
        ]);

        return redirect()->route('reservas.confirmacion', $reserva->id)->with('success', 'Reserva creada exitosamente');
    }

    public function confirmacionReserva($id)
    {
        $reserva = Reserva::with(['pista.complejo', 'user'])->findOrFail($id);
        
        // Verificar que la reserva pertenece al usuario autenticado
        if ($reserva->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver esta reserva');
        }
        
        return view('reservas.confirmacion', compact('reserva'));
    }

    public function contacto()
    {
        return view('contacto');
    }

    public function contactosPadel()
    {
        return view('contactos-padel');
    }

    public function enviarContacto(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        try {
            \Illuminate\Support\Facades\Mail::to('23cheredia@gmail.com')
                ->send(new \App\Mail\ContactoMail(
                    $validated['name'],
                    $validated['email'],
                    $validated['message']
                ));

            return redirect()->route('contacto')
                ->with('success', 'Mensaje enviado correctamente. Te contactaremos pronto.');
        } catch (\Exception $e) {
            return redirect()->route('contacto')
                ->with('error', 'Hubo un problema al enviar el mensaje. Por favor, intenta de nuevo.');
        }
    }

    public function misReservas()
    {
        $reservas = Reserva::with(['pista.complejo'])
            ->where('user_id', Auth::id())
            ->orderBy('fecha_reserva', 'desc')
            ->orderBy('hora_inicio', 'desc')
            ->get();
            
        return view('reservas.mis-reservas', compact('reservas'));
    }

    public function editarReserva($id)
    {
        $reserva = Reserva::with(['pista.complejo'])->findOrFail($id);
        
        // Verificar que la reserva pertenece al usuario autenticado
        if ($reserva->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar esta reserva');
        }
        
        // Verificar que la reserva es futura
        if ($reserva->fecha_reserva < now()->format('Y-m-d')) {
            return redirect()->route('reservas.mis-reservas')
                ->with('error', 'No puedes editar una reserva pasada');
        }
        
        // Obtener horarios disponibles del complejo
        $complejo = $reserva->pista->complejo;
        $horariosDisponibles = [];
        
        if ($complejo->hora_apertura && $complejo->hora_cierre) {
            $horaInicio = intval(substr($complejo->hora_apertura, 0, 2));
            $horaFin = intval(substr($complejo->hora_cierre, 0, 2));
            
            for ($hora = $horaInicio; $hora < $horaFin; $hora++) {
                $horariosDisponibles[] = str_pad($hora, 2, '0', STR_PAD_LEFT) . ':00';
            }
        } else {
            // Horarios por defecto si no están configurados
            for ($hora = 8; $hora < 22; $hora++) {
                $horariosDisponibles[] = str_pad($hora, 2, '0', STR_PAD_LEFT) . ':00';
            }
        }
        
        return view('reservas.editar', compact('reserva', 'horariosDisponibles'));
    }

    public function actualizarReserva(Request $request, $id)
    {
        $reserva = Reserva::findOrFail($id);
        
        // Verificar que la reserva pertenece al usuario autenticado
        if ($reserva->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar esta reserva');
        }
        
        // Verificar que la reserva es futura
        if ($reserva->fecha_reserva < now()->format('Y-m-d')) {
            return redirect()->route('reservas.mis-reservas')
                ->with('error', 'No puedes editar una reserva pasada');
        }
        
        // Validar campos
        $validated = $request->validate([
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i',
        ]);
        
        // Validar que la hora de fin sea después de la hora de inicio
        $horaInicioMinutos = intval(substr($validated['hora_inicio'], 0, 2)) * 60 + intval(substr($validated['hora_inicio'], 3, 2));
        $horaFinMinutos = intval(substr($validated['hora_fin'], 0, 2)) * 60 + intval(substr($validated['hora_fin'], 3, 2));
        
        if ($horaFinMinutos <= $horaInicioMinutos) {
            return back()->withErrors([
                'hora_fin' => 'La hora de fin debe ser posterior a la hora de inicio.'
            ])->withInput();
        }
        
        // Verificar disponibilidad (excluyendo la reserva actual)
        $reservasExistentes = Reserva::where('pista_id', $reserva->pista_id)
            ->where('fecha_reserva', $validated['fecha'])
            ->where('estado', '!=', 'cancelada')
            ->where('id', '!=', $id)
            ->get();
        
        foreach ($reservasExistentes as $reservaExistente) {
            $reservaInicioMinutos = intval(substr($reservaExistente->hora_inicio, 0, 2)) * 60 + intval(substr($reservaExistente->hora_inicio, 3, 2));
            $reservaFinMinutos = intval(substr($reservaExistente->hora_fin, 0, 2)) * 60 + intval(substr($reservaExistente->hora_fin, 3, 2));
            
            if ($horaInicioMinutos < $reservaFinMinutos && $horaFinMinutos > $reservaInicioMinutos) {
                return back()->withErrors([
                    'horario' => 'El horario seleccionado no está disponible. Por favor, elige otro horario.'
                ])->withInput();
            }
        }
        
        // Calcular nuevo precio total
        $pista = $reserva->pista;
        $horas = ($horaFinMinutos - $horaInicioMinutos) / 60;
        $precioTotal = $horas * $pista->precio_hora;
        
        // Actualizar reserva
        $reserva->update([
            'fecha_reserva' => $validated['fecha'],
            'hora_inicio' => $validated['hora_inicio'],
            'hora_fin' => $validated['hora_fin'],
            'precio_total' => $precioTotal,
        ]);
        
        return redirect()->route('reservas.mis-reservas')
            ->with('success', 'Reserva actualizada exitosamente');
    }

    public function cancelarReserva($id)
    {
        $reserva = Reserva::findOrFail($id);
        
        // Verificar que la reserva pertenece al usuario autenticado
        if ($reserva->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para cancelar esta reserva');
        }
        
        // Verificar que la reserva es futura
        if ($reserva->fecha_reserva < now()->format('Y-m-d')) {
            return redirect()->route('reservas.mis-reservas')
                ->with('error', 'No puedes cancelar una reserva pasada');
        }
        
        // Cambiar estado a cancelada
        $reserva->update(['estado' => 'cancelada']);
        
        return redirect()->route('reservas.mis-reservas')
            ->with('success', 'Reserva cancelada exitosamente');
    }
}