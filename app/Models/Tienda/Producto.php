<?php

namespace App\Models\Tienda;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'imagen',
        'imagenes',
        'destacado',
        'novedad',
        'orden'
    ];

    protected $casts = [
        'destacado' => 'boolean',
        'novedad' => 'boolean',
        'precio' => 'decimal:2',
        'imagenes' => 'array',
    ];

    /**
     * Relación con categoría
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    /**
     * Relación con pedidos
     */
    public function pedidos()
    {
        return $this->belongsToMany(Pedido::class, 'pedido_producto')
            ->withPivot('cantidad', 'precio_unitario')
            ->withTimestamps();
    }
}
