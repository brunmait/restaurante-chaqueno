<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'uuid', 'pedido_id', 'concepto', 'monto_centavos', 'currency',
        'status', 'gateway', 'gateway_payment_id', 'checkout_url'
    ];
    
    public function pedido()
    {
        return $this->belongsTo(\DB::class, 'pedido_id');
    }
}
