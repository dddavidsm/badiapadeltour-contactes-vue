<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complejo extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'nombre',
        'descripcion',
        'direccion',
        'activo',
        'hora_apertura',
        'hora_cierre',
        'imagen'
    ];

    public function pistas()
    {
        return $this->hasMany(Pista::class);
    }
}