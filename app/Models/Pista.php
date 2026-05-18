<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pista extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'complejo_id',
        'nombre',
        'tipo',
        'es_dobles',
        'precio_hora',
        'disponible',
        'imagen'
    ];

    public function complejo()
    {
        return $this->belongsTo(Complejo::class);
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
}