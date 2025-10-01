<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    protected $table = 'reportes';
    
    protected $fillable = [
        'tipo',
        'titulo',
        'datos',
        'fecha_reporte',
        'total_ventas',
        'total_pedidos',
        'usuario_id'
    ];

    protected $casts = [
        'fecha_reporte' => 'date',
        'total_ventas' => 'decimal:2',
        'total_pedidos' => 'integer',
        'datos' => 'array'
    ];
}