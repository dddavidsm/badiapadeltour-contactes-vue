<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'apellidos',
        'dni',
        'telefono',
        'email',
        'password',
        'role',
        'talla_pie',
        'talla_camiseta',
        'talla_pantalon',
        'altura',
        'peso',
        'nivel_juego',
        'mano_dominante',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relaciones
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    public function pedidos()
    {
        return $this->hasMany(\App\Models\Tienda\Pedido::class);
    }

    public function torneos()
    {
        return $this->belongsToMany(Tournament::class, 'tournament_participants')
            ->withPivot('puntos_obtenidos', 'posicion_final')
            ->withTimestamps();
    }
    
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}