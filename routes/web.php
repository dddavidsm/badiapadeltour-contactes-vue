<?php

use App\Http\Controllers\Bpt\ProfileController;
use App\Http\Controllers\Bpt\PageController;
use App\Http\Controllers\Tienda\ProductoController;
use App\Http\Controllers\Tienda\CarritoController;
use App\Http\Controllers\Tienda\PedidoController;
use App\Http\Controllers\Bpt\TournamentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (Cualquiera puede verlas)
|--------------------------------------------------------------------------
*/

// Portada
Route::get('/', [PageController::class, 'home'])->name('home');

// Servir imágenes desde resources/images o public/images
Route::get('/images/{file}', function ($file) {
    $pathResource = resource_path('images/' . $file);
    $pathPublic = public_path('images/' . $file);

    if (file_exists($pathResource)) {
        return Response::file($pathResource);
    }

    if (file_exists($pathPublic)) {
        return Response::file($pathPublic);
    }

    abort(404);
})->where('file', '.*');

// Complejos
Route::get('/complejos', [PageController::class, 'complejos'])
    ->name('complejos.index');

// Pistas
Route::get('/pistas', [PageController::class, 'pistas'])
    ->name('pistas.index');

// Detalle pista
Route::get('/pistas/{id}', [PageController::class, 'pistaDetalle'])
    ->name('pistas.detalle');

// Página de contacto
Route::get('/contacto', [PageController::class, 'contacto'])
    ->name('contacto');
Route::post('/contacto', [PageController::class, 'enviarContacto'])
    ->name('contacto.enviar');

// Buscar pareja de pádel (Vue)
Route::get('/contactos-padel', [PageController::class, 'contactosPadel'])
    ->name('contactos.padel');

// Página informativa de tienda (landing / info)
Route::get('/tienda', [PageController::class, 'tienda'])
    ->name('tienda.index');

/*
|--------------------------------------------------------------------------| TORNEOS
--------------------------------------------------------------------------
*/

Route::prefix('torneos')->group(function () {
    Route::get('/', [TournamentController::class, 'index'])->name('torneos.index');
    Route::get('/mis-torneos', [TournamentController::class, 'misTorneos'])
        ->middleware('auth')
        ->name('torneos.mis-torneos');
    Route::get('/{id}', [TournamentController::class, 'show'])->name('torneos.show');
    Route::post('/{id}/inscribir', [TournamentController::class, 'inscribir'])
        ->middleware('auth')
        ->name('torneos.inscribir');
});

/*
--------------------------------------------------------------------------| RESERVAS (requiere autenticación)
|--------------------------------------------------------------------------
*/

// Formulario de reserva
Route::get('/reservar/{pistaId}', [PageController::class, 'reservarFormulario'])
    ->middleware('auth')
    ->name('reservas.formulario');

// Guardar reserva
Route::post('/reservar/{pistaId}', [PageController::class, 'guardarReserva'])
    ->middleware('auth')
    ->name('reservas.guardar');

// Confirmación de reserva
Route::get('/reserva/confirmacion/{id}', [PageController::class, 'confirmacionReserva'])
    ->middleware('auth')
    ->name('reservas.confirmacion');

// Mis reservas
Route::get('/mis-reservas', [PageController::class, 'misReservas'])
    ->middleware('auth')
    ->name('reservas.mis-reservas');

// Editar reserva (formulario)
Route::get('/reserva/{id}/editar', [PageController::class, 'editarReserva'])
    ->middleware('auth')
    ->name('reservas.editar');

// Actualizar reserva
Route::put('/reserva/{id}', [PageController::class, 'actualizarReserva'])
    ->middleware('auth')
    ->name('reservas.actualizar');

// Cancelar reserva
Route::delete('/reserva/{id}', [PageController::class, 'cancelarReserva'])
    ->middleware('auth')
    ->name('reservas.cancelar');

/*
|--------------------------------------------------------------------------
| TIENDA - Productos y Carrito (Público)
|--------------------------------------------------------------------------
*/

Route::prefix('tienda')->group(function () {

    // Listado de productos (público)
    Route::get('/productos', [ProductoController::class, 'index'])
        ->name('tienda.productos');

    // Detalle de producto (público)
    Route::get('/productos/{id}', [ProductoController::class, 'show'])
        ->name('tienda.producto');

    // Carrito (público)
    Route::get('/carrito', [CarritoController::class, 'index'])
        ->name('tienda.carrito');

    Route::get('/carrito/add/{id}', [CarritoController::class, 'add'])
        ->name('tienda.carrito.add');

    Route::delete('/carrito/remove/{id}', [CarritoController::class, 'remove'])
        ->name('tienda.carrito.remove');

    Route::post('/carrito/update/{id}', [CarritoController::class, 'updateQuantity'])
        ->name('tienda.carrito.update');

    Route::get('/carrito/clear', [CarritoController::class, 'clear'])
        ->name('tienda.carrito.clear');

    // Checkout (página estética, no funcional)
    Route::get('/checkout', function () {
        return view('tienda.checkout');
    })->name('tienda.checkout');
});

/*
|--------------------------------------------------------------------------
| TIENDA - Pedidos (Requiere Login)
|--------------------------------------------------------------------------
*/

Route::prefix('tienda')->middleware('auth')->group(function () {
    // Pedido (requiere login)
    Route::post('/pedido', [PedidoController::class, 'store'])
        ->name('tienda.pedido.store');

    Route::get('/pedidos', [PedidoController::class, 'index'])
        ->name('tienda.pedidos');

});

/*
|--------------------------------------------------------------------------
| RUTAS PRIVADAS (Dashboard / Perfil)
|--------------------------------------------------------------------------
*/

// API para obtener horarios disponibles
Route::get('/api/disponibilidad/{pistaId}/{fecha}', function ($pistaId, $fecha) {
    $pista = \App\Models\Pista::findOrFail($pistaId);
    
    // Horarios disponibles de 8 AM a 10 PM
    $horariosBase = [];
    for ($hora = 8; $hora < 22; $hora++) {
        $horariosBase[] = str_pad($hora, 2, '0', STR_PAD_LEFT) . ':00';
    }
    
    // Obtener reservas para ese día
    $reservas = \App\Models\Reserva::where('pista_id', $pistaId)
        ->where('fecha_reserva', $fecha)
        ->where('estado', '!=', 'cancelada')
        ->get(['hora_inicio', 'hora_fin']);
    
    // Marcar horarios como disponibles o no
    $horarios = [];
    foreach ($horariosBase as $hora) {
        $disponible = true;
        
        foreach ($reservas as $reserva) {
            $inicio = strtotime($reserva->hora_inicio);
            $fin = strtotime($reserva->hora_fin);
            $horaTiempo = strtotime($hora);
            
            // Si el horario está dentro de una reserva existente
            if ($horaTiempo >= $inicio && $horaTiempo < $fin) {
                $disponible = false;
                break;
            }
        }
        
        $horarios[] = [
            'hora' => $hora,
            'disponible' => $disponible
        ];
    }
    
    return response()->json($horarios);
});

// Dashboard de usuario (perfil completo)
Route::get('/dashboard', [ProfileController::class, 'show'])->middleware(['auth', 'verified'])->name('dashboard');

// Perfil (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/medidas', [ProfileController::class, 'updateMedidas'])->name('profile.update-medidas');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| AUTENTICACIÓN
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
