<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

echo "🔧 Creando tabla productos...\n";

if (!Schema::hasTable('productos')) {
    Schema::create('productos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
        $table->string('nombre');
        $table->text('descripcion')->nullable();
        $table->decimal('precio', 8, 2);
        $table->integer('stock')->default(0);
        $table->string('imagen')->nullable();
        $table->boolean('disponible')->default(true);
        $table->timestamps();
    });
    echo "✅ Tabla productos creada\n";
} else {
    echo "⚠️  Tabla productos ya existe\n";
}

// Insertar productos de ejemplo si la tabla está vacía
$count = DB::table('productos')->count();
if ($count == 0) {
    DB::table('productos')->insert([
        [
            'categoria_id' => 1,
            'nombre' => 'Chancho a la Cruz - Porción 1',
            'descripcion' => 'Deliciosa carne de chancho asada a la cruz',
            'precio' => 50.00,
            'stock' => 20,
            'disponible' => true,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'categoria_id' => 2,
            'nombre' => 'Pollo a la Leña - 1/4',
            'descripcion' => 'Cuarto de pollo asado a la leña',
            'precio' => 25.00,
            'stock' => 30,
            'disponible' => true,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'categoria_id' => 3,
            'nombre' => 'Coca Cola 500ml',
            'descripcion' => 'Refresco de cola',
            'precio' => 8.00,
            'stock' => 50,
            'disponible' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]
    ]);
    echo "✅ Productos de ejemplo insertados\n";
}

echo "🚀 Completado!\n";