<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'usuarios';
    public $timestamps = false;
    protected $fillable = ['nombre', 'email', 'contraseña', 'rol_id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['contrasena'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'rol_id');
    }

    public function getPasswordAttribute(): ?string
    {
        // Soporta 'contrasena' o 'contraseña'
        if (array_key_exists('contrasena', $this->attributes)) {
            return $this->attributes['contrasena'];
        }
        $key = 'contraseña';
        return $this->attributes[$key] ?? null;
    }

    // Usado por el sistema de autenticación para obtener el hash de contraseña
    public function getAuthPassword()
    {
        return $this->password; // delega al accessor anterior
    }

    public function getNameAttribute(): ?string
    {
        return $this->attributes['nombre'] ?? null;
    }
}
