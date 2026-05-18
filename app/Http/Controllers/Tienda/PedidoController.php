<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Models\Tienda\Pedido;
use App\Models\Tienda\Producto;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    public function store()
    {
        $carrito = session()->get('carrito');

        if (!$carrito) {
            return redirect()->route('tienda.productos')->with('error', 'El carrito está vacío');
        }

        // Verificar stock disponible
        foreach ($carrito as $id => $item) {
            $producto = Producto::find($id);
            
            if (!$producto) {
                return redirect()->route('tienda.carrito')
                    ->with('error', "El producto {$item['nombre']} ya no está disponible");
            }
            
            if ($producto->stock < $item['cantidad']) {
                return redirect()->route('tienda.carrito')
                    ->with('error', "Stock insuficiente para {$producto->nombre}. Solo quedan {$producto->stock} unidades");
            }
        }

        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        $pedido = Pedido::create([
            'user_id' => Auth::id(),
            'total' => $total,
            'estado' => 'pendiente'
        ]);

        // Guardar productos y descontar stock
        foreach ($carrito as $id => $item) {
            $pedido->productos()->attach($id, [
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $item['precio']
            ]);
            
            // Descontar stock
            $producto = Producto::find($id);
            $producto->decrement('stock', $item['cantidad']);
        }

        session()->forget('carrito');

        return redirect()->route('tienda.pedidos')->with('success', '¡Pedido realizado con éxito!');
    }

    public function index()
    {
        $pedidos = Pedido::where('user_id', Auth::id())->get();
        return view('tienda.pedido.index', compact('pedidos'));
    }
}
