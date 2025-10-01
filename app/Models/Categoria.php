<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'color',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function productosDisponibles()
    {
        return $this->hasMany(Producto::class)->where('disponible', true);
    }
}