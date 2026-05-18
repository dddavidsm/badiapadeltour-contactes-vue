<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Obtiene el usuario asociado a la reserva
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    /**
     * Obtiene la pista asociada a la reserva
     */
    public function pista()
    {
        return $this->belongsTo(Pista::class)->withDefault();
    }
}
