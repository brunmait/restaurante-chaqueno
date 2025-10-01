<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $fillable = [
        'proveedor_id',
        'fecha_compra',
        'total',
        'observaciones'
    ];

    protected $casts = [
        'fecha_compra' => 'date',
        'total' => 'decimal:2'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function items()
    {
        return $this->hasMany(CompraItem::class);
    }
}