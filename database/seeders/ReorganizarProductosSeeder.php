<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReorganizarProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar todo
        DB::table('stock')->delete();
        DB::table('productos')->delete();
        
        $this->command->info('Productos y stock eliminados. Creando nueva estructura...');

        // 1. INSUMOS (materia prima)
        $insumos = [
            [
                'nombre' => 'Carne de chancho',
                'descripcion' => 'Materia prima para platos de chancho (se compra por kilos)',
                'precio' => 45.00, // Bs. 45 por kilo
                'categoria' => 'insumo',
                'stock' => 0, // Se calculará automáticamente
                'imagen' => '',
            ],
            [
                'nombre' => 'Pollo entero',
                'descripcion' => 'Materia prima para platos de pollo (se compra por unidad)',
                'precio' => 55.00, // Bs. 55 por pollo
                'categoria' => 'insumo',
                'stock' => 0, // Se calculará automáticamente
                'imagen' => '',
            ],
        ];

        foreach ($insumos as $insumo) {
            DB::table('productos')->insert($insumo);
        }

        // 2. PLATOS DE CHANCHO (se calculan automáticamente)
        $platosChancho = [
            [
                'nombre' => 'Chancho a la Cruz - 2 Costillas',
                'descripcion' => 'Delicioso chancho a la cruz con 2 costillas, preparado con especias tradicionales',
                'precio' => 60.00,
                'categoria' => 'chancho',
                'stock' => 0, // Se calcula automáticamente
                'imagen' => '',
            ],
            [
                'nombre' => 'Chancho a la Cruz - 2.5 Costillas',
                'descripcion' => 'Chancho a la cruz con 2.5 costillas, ideal para compartir',
                'precio' => 70.00,
                'categoria' => 'chancho',
                'stock' => 0, // Se calcula automáticamente
                'imagen' => '',
            ],
            [
                'nombre' => 'Chancho a la Cruz - 3 Costillas',
                'descripcion' => 'Porción generosa de chancho a la cruz con 3 costillas',
                'precio' => 80.00,
                'categoria' => 'chancho',
                'stock' => 0, // Se calcula automáticamente
                'imagen' => '',
            ],
            [
                'nombre' => 'Chancho a la Cruz - 3.5 Costillas',
                'descripcion' => 'La porción más grande de chancho a la cruz con 3.5 costillas',
                'precio' => 90.00,
                'categoria' => 'chancho',
                'stock' => 0, // Se calcula automáticamente
                'imagen' => '',
            ],
        ];

        foreach ($platosChancho as $plato) {
            DB::table('productos')->insert($plato);
        }

        // 3. PLATOS DE POLLO (se calculan automáticamente)
        $platosPollo = [
            [
                'nombre' => 'Pollo a la Leña',
                'descripcion' => 'Pollo entero a la leña, marinado con especias y hierbas aromáticas',
                'precio' => 60.00,
                'categoria' => 'pollo',
                'stock' => 0, // Se calcula automáticamente
                'imagen' => '',
            ],
            [
                'nombre' => 'Pollo a la Leña - Media Porción',
                'descripcion' => 'Media porción de pollo a la leña, perfecta para una persona',
                'precio' => 35.00,
                'categoria' => 'pollo',
                'stock' => 0, // Se calcula automáticamente
                'imagen' => '',
            ],
        ];

        foreach ($platosPollo as $plato) {
            DB::table('productos')->insert($plato);
        }

        // 4. REFRESCOS (por unidades)
        $refrescos = [
            [
                'nombre' => 'Coca Cola 500ml',
                'descripcion' => 'Refresco Coca Cola de 500ml bien frío',
                'precio' => 8.00,
                'categoria' => 'refresco',
                'stock' => 5, // 5 unidades
                'imagen' => '',
            ],
            [
                'nombre' => 'Fanta 500ml',
                'descripcion' => 'Refresco Fanta naranja de 500ml',
                'precio' => 8.00,
                'categoria' => 'refresco',
                'stock' => 5, // 5 unidades
                'imagen' => '',
            ],
            [
                'nombre' => 'Sprite 500ml',
                'descripcion' => 'Refresco Sprite de 500ml',
                'precio' => 8.00,
                'categoria' => 'refresco',
                'stock' => 5, // 5 unidades
                'imagen' => '',
            ],
            [
                'nombre' => 'Agua Mineral 600ml',
                'descripcion' => 'Agua mineral natural de 600ml',
                'precio' => 5.00,
                'categoria' => 'refresco',
                'stock' => 5, // 5 unidades
                'imagen' => '',
            ],
        ];

        foreach ($refrescos as $refresco) {
            DB::table('productos')->insert($refresco);
        }

        // 5. AGREGAR STOCK DE INSUMOS
        $chanchoId = DB::table('productos')->where('nombre', 'Carne de chancho')->value('id');
        $polloId = DB::table('productos')->where('nombre', 'Pollo entero')->value('id');

        // Para 5 platos de chancho, necesitamos calcular los kilos
        // Asumiendo que queremos 5 platos de 3 costillas (el más popular)
        // 3 costillas = 600g = 0.6kg por plato
        // 5 platos × 0.6kg = 3kg de carne
        $kilosChancho = 3.0; // 3 kilos para 5 platos de 3 costillas

        // Agregar stock de chancho
        DB::table('stock')->insert([
            'producto_id' => $chanchoId,
            'cantidad' => $kilosChancho,
            'tipo' => 'entrada',
            'fecha' => now(),
        ]);

        // Agregar 5 pollos enteros
        DB::table('stock')->insert([
            'producto_id' => $polloId,
            'cantidad' => 5,
            'tipo' => 'entrada',
            'fecha' => now(),
        ]);

        // Actualizar stock en productos
        DB::table('productos')->where('id', $chanchoId)->update(['stock' => $kilosChancho]);
        DB::table('productos')->where('id', $polloId)->update(['stock' => 5]);

        $this->command->info('Estructura creada exitosamente:');
        $this->command->info("- {$kilosChancho} kg de carne de chancho (para ~5 platos de 3 costillas)");
        $this->command->info('- 5 pollos enteros');
        $this->command->info('- 5 unidades de cada refresco');
    }
}
