<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabla de proveedores
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Tabla de compras
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedores');
            $table->date('fecha_compra');
            $table->decimal('total', 10, 2);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        // Tabla de items de compra
        Schema::create('compra_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compra_id')->constrained('compras')->onDelete('cascade');
            $table->string('producto'); // 'costillar_entero'
            $table->integer('cantidad'); // cantidad de costillares
            $table->decimal('precio_unitario', 8, 2); // 700 Bs por costillar
            $table->integer('costillas_totales'); // 18 * cantidad
            $table->decimal('costo_por_costilla', 8, 2); // 700/18 = 38.89
            $table->timestamps();
        });

        // Actualizar tabla stock_costillas para incluir costo
        Schema::table('stock_costillas', function (Blueprint $table) {
            $table->decimal('costo_promedio', 8, 2)->default(38.89)->after('costillas_completas');
            $table->integer('stock_minimo')->default(10)->after('costo_promedio');
        });

        // Insertar proveedor por defecto
        DB::table('proveedores')->insert([
            'nombre' => 'Proveedor Principal',
            'telefono' => '70123456',
            'direccion' => 'Mercado Central',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down()
    {
        Schema::table('stock_costillas', function (Blueprint $table) {
            $table->dropColumn(['costo_promedio', 'stock_minimo']);
        });
        Schema::dropIfExists('compra_items');
        Schema::dropIfExists('compras');
        Schema::dropIfExists('proveedores');
    }
};