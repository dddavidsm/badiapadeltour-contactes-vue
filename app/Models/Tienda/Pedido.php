<?php

namespace App\Models\Tienda;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = [
        'user_id',
        'total',
        'estado',
        'direccion_envio'
    ];

    /**
     * Relación con productos
     */
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'pedido_producto')
            ->withPivot('cantidad', 'precio_unitario')
            ->withTimestamps();
    }

    /**
     * Relación con usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
