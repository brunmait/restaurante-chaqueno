<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabla para el stock de carne costilla completa
        Schema::create('stock_costillas', function (Blueprint $table) {
            $table->id();
            $table->decimal('costillas_completas', 8, 2)->default(0); // Cantidad de carnes de 18 costillas
            $table->decimal('costo_por_carne', 8, 2)->default(700); // 700 Bs por 18 costillas
            $table->timestamps();
        });

        // Tabla para las ventas de costillas
        Schema::create('ventas_costillas', function (Blueprint $table) {
            $table->id();
            $table->decimal('cantidad_costillas', 8, 1); // 1, 1.5, 2, 2.5, 3
            $table->decimal('precio_unitario', 8, 2); // 50, 60, 70, 80, 90
            $table->decimal('total', 8, 2);
            $table->string('cliente_nombre')->nullable();
            $table->string('tipo')->default('venta_directa'); // venta_directa, pedido_online
            $table->string('numero_ticket', 10)->nullable(); // Referencia al ticket
            $table->timestamps();
        });

        // Insertar stock inicial
        DB::table('stock_costillas')->insert([
            'costillas_completas' => 0,
            'costo_por_carne' => 700,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('ventas_costillas');
        Schema::dropIfExists('stock_costillas');
    }
};