<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pedidos_online', function (Blueprint $table) {
            $table->id();
            $table->string('numero_ticket', 10)->unique();
            $table->string('cliente_nombre');
            $table->string('cliente_telefono');
            $table->json('items'); // [{tipo: 'costilla', cantidad: '1.5', acompanamiento: 'arroz', precio: 60}]
            $table->decimal('total', 10, 2);
            $table->enum('estado', ['pendiente', 'preparando', 'listo', 'entregado', 'cancelado'])->default('pendiente');
            $table->timestamp('fecha_pedido');
            $table->timestamp('fecha_entrega')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedidos_online');
    }
};