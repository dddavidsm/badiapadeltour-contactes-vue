<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    /**
     * Obtiene el complejo asociado al torneo
     */
    public function complejo()
    {
        return $this->belongsTo(Complejo::class);
    }

    /**
     * Obtiene los participantes del torneo
     */
    public function participantes()
    {
        return $this->hasMany(TournamentParticipant::class);
    }

    /**
     * Obtiene los usuarios inscritos en el torneo
     */
    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'tournament_participants')
            ->withPivot('posicion', 'estado')
            ->withTimestamps();
    }

    /**
     * Verifica si el torneo está abierto para inscripciones
     */
    public function estaAbierto()
    {
        return $this->estado === 'Abierto' && $this->participantes_actuales < $this->max_participantes;
    }

    /**
     * Verifica si un usuario está inscrito
     */
    public function usuarioInscrito($userId)
    {
        return $this->participantes()->where('user_id', $userId)->exists();
    }

    /**
     * Incrementa el contador de participantes
     */
    public function incrementarParticipantes()
    {
        $this->increment('participantes_actuales');
    }
}
