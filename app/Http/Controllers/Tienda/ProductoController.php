<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Models\Tienda\Producto;
use App\Models\Tienda\Categoria;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with('categoria');
        
        // Filtro por categoría
        if ($request->has('categoria') && $request->categoria != '') {
            $query->where('categoria_id', $request->categoria);
        }
        
        // Filtro por destacados
        if ($request->has('destacados')) {
            $query->where('destacado', true);
        }
        
        // Filtro por novedades
        if ($request->has('novedades')) {
            $query->where('novedad', true);
        }
        
        // Ordenamiento
        $orden = $request->get('orden', 'nombre_asc');
        switch ($orden) {
            case 'precio_asc':
                $query->orderBy('precio', 'asc');
                break;
            case 'precio_desc':
                $query->orderBy('precio', 'desc');
                break;
            case 'nombre_desc':
                $query->orderBy('nombre', 'desc');
                break;
            case 'destacados':
                $query->orderBy('destacado', 'desc')->orderBy('orden', 'asc');
                break;
            default: // nombre_asc
                $query->orderBy('nombre', 'asc');
                break;
        }
        
        $productos = $query->paginate(12)->withQueryString();
        $categorias = Categoria::withCount('productos')->orderBy('orden')->get();
        
        // Obtener categoría seleccionada si existe
        $categoriaSeleccionada = null;
        if ($request->has('categoria') && $request->categoria != '') {
            $categoriaSeleccionada = Categoria::find($request->categoria);
        }
        
        return view('tienda.productos.index', compact('productos', 'categorias', 'categoriaSeleccionada'));
    }

    public function show($id)
    {
        $producto = Producto::with('categoria')->findOrFail($id);
        $relacionados = Producto::where('categoria_id', $producto->categoria_id)
            ->where('id', '!=', $id)
            ->take(4)
            ->get();
        
        return view('tienda.productos.show', compact('producto', 'relacionados'));
    }
}
