<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Models\Tienda\Producto;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    public function index()
    {
        $carrito = session()->get('carrito', []);
        return view('tienda.carrito.index', compact('carrito'));
    }

    public function add($id)
    {
        $producto = Producto::findOrFail($id);
        
        if ($producto->stock <= 0) {
            return redirect()->back()->with('error', 'Producto sin stock disponible');
        }
        
        $carrito = session()->get('carrito', []);

        if (isset($carrito[$id])) {
            // Verificar que no se exceda el stock
            if ($carrito[$id]['cantidad'] >= $producto->stock) {
                return redirect()->back()->with('error', "Stock máximo alcanzado para {$producto->nombre} ({$producto->stock} unidades)");
            }
            $carrito[$id]['cantidad']++;
        } else {
            $carrito[$id] = [
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => 1,
                'imagen' => $producto->imagen
            ];
        }

        session()->put('carrito', $carrito);
        return redirect()->back()->with('success', '✓ Producto añadido al carrito');
    }

    public function remove($id)
    {
        $carrito = session()->get('carrito', []);
        
        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            session()->put('carrito', $carrito);
        }
        
        return redirect()->route('tienda.carrito')->with('success', 'Producto eliminado del carrito');
    }

    public function updateQuantity(Request $request, $id)
    {
        // Siempre devolver JSON para peticiones AJAX/Fetch
        if ($request->isMethod('post') && ($request->header('Content-Type') === 'application/json' || $request->expectsJson())) {
            $producto = Producto::findOrFail($id);
            $carrito = session()->get('carrito', []);
            
            if (!isset($carrito[$id])) {
                return response()->json([
                    'error' => 'Producto no encontrado en el carrito'
                ], 404);
            }
            
            $cantidad = max(1, intval($request->cantidad ?? 0));
            
            // Verificar que no se exceda el stock disponible
            if ($cantidad > $producto->stock) {
                return response()->json([
                    'error' => "Stock insuficiente para {$producto->nombre}. Solo quedan {$producto->stock} unidades"
                ], 422);
            }
            
            $carrito[$id]['cantidad'] = $cantidad;
            session()->put('carrito', $carrito);
            
            return response()->json([
                'success' => true,
                'cantidad' => $cantidad,
                'mensaje' => 'Cantidad actualizada correctamente'
            ]);
        }
        
        // Petición tradicional (no AJAX)
        $producto = Producto::findOrFail($id);
        $carrito = session()->get('carrito', []);
        
        if (isset($carrito[$id])) {
            $cantidad = max(1, intval($request->cantidad ?? 0));
            
            // Verificar que no se exceda el stock disponible
            if ($cantidad > $producto->stock) {
                return redirect()->route('tienda.carrito')
                    ->with('error', "Stock insuficiente para {$producto->nombre}. Solo quedan {$producto->stock} unidades");
            }
            
            $carrito[$id]['cantidad'] = $cantidad;
            session()->put('carrito', $carrito);
        }
        
        return redirect()->route('tienda.carrito');
    }

    public function clear()
    {
        session()->forget('carrito');
        return redirect()->route('tienda.carrito')->with('success', 'Carrito vaciado');
    }
}
