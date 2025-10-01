<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Eliminar tabla productos existente si existe
        Schema::dropIfExists('productos');
        
        // Tabla de categorías
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->string('icono')->default('fas fa-utensils');
            $table->string('color')->default('#ef4444');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Tabla de productos nueva
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

        // Insertar categorías por defecto
        DB::table('categorias')->insert([
            [
                'nombre' => 'Carnes',
                'descripcion' => 'Chancho a la cruz y otras carnes',
                'icono' => 'fas fa-bacon',
                'color' => '#dc2626',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Pollo',
                'descripcion' => 'Pollo a la leña y preparaciones',
                'icono' => 'fas fa-drumstick-bite',
                'color' => '#f59e0b',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Bebidas',
                'descripcion' => 'Refrescos, jugos y bebidas',
                'icono' => 'fas fa-glass-cheers',
                'color' => '#3b82f6',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Insertar productos de ejemplo
        DB::table('productos')->insert([
            // Carnes
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
                'categoria_id' => 1,
                'nombre' => 'Chancho a la Cruz - Porción 1.5',
                'descripcion' => 'Porción mediana de chancho a la cruz',
                'precio' => 60.00,
                'stock' => 15,
                'disponible' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Pollo
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
                'categoria_id' => 2,
                'nombre' => 'Pollo a la Leña - 1/2',
                'descripcion' => 'Medio pollo asado a la leña',
                'precio' => 45.00,
                'stock' => 20,
                'disponible' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Bebidas
            [
                'categoria_id' => 3,
                'nombre' => 'Coca Cola 500ml',
                'descripcion' => 'Refresco de cola',
                'precio' => 8.00,
                'stock' => 50,
                'disponible' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'categoria_id' => 3,
                'nombre' => 'Agua Mineral 500ml',
                'descripcion' => 'Agua mineral natural',
                'precio' => 5.00,
                'stock' => 40,
                'disponible' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('productos');
        Schema::dropIfExists('categorias');
    }
};