<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompraItem extends Model
{
    protected $table = 'compra_items';
    
    protected $fillable = [
        'compra_id',
        'producto',
        'cantidad',
        'precio_unitario',
        'costillas_totales',
        'costo_por_costilla'
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'costo_por_costilla' => 'decimal:2'
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }
}