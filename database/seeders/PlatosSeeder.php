<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlatosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar platos existentes (excepto insumos) para evitar duplicados
        DB::table('productos')->where('categoria', '!=', 'insumo')->delete();
        $this->command->info('Platos existentes eliminados. Agregando nuevos platos...');

        $platos = [
            // Platos de Chancho (se calculan automáticamente según el stock de carne)
            [
                'nombre' => 'Chancho a la Cruz - 2 Costillas',
                'descripcion' => 'Delicioso chancho a la cruz con 2 costillas, preparado con especias tradicionales',
                'precio' => 60.00,
                'categoria' => 'chancho',
                'stock' => 0, // Se calcula automáticamente
                'imagen' => null,
            ],
            [
                'nombre' => 'Chancho a la Cruz - 2.5 Costillas',
                'descripcion' => 'Chancho a la cruz con 2.5 costillas, ideal para compartir',
                'precio' => 70.00,
                'categoria' => 'chancho',
                'stock' => 0, // Se calcula automáticamente
                'imagen' => null,
            ],
            [
                'nombre' => 'Chancho a la Cruz - 3 Costillas',
                'descripcion' => 'Porción generosa de chancho a la cruz con 3 costillas',
                'precio' => 80.00,
                'categoria' => 'chancho',
                'stock' => 0, // Se calcula automáticamente
                'imagen' => null,
            ],
            [
                'nombre' => 'Chancho a la Cruz - 3.5 Costillas',
                'descripcion' => 'La porción más grande de chancho a la cruz con 3.5 costillas',
                'precio' => 90.00,
                'categoria' => 'chancho',
                'stock' => 0, // Se calcula automáticamente
                'imagen' => null,
            ],
            
            // Platos de Pollo (se calculan automáticamente según el stock de pollos)
            [
                'nombre' => 'Pollo a la Leña',
                'descripcion' => 'Pollo entero a la leña, marinado con especias y hierbas aromáticas',
                'precio' => 60.00,
                'categoria' => 'pollo',
                'stock' => 0, // Se calcula automáticamente
                'imagen' => null,
            ],
            [
                'nombre' => 'Pollo a la Leña - Media Porción',
                'descripcion' => 'Media porción de pollo a la leña, perfecta para una persona',
                'precio' => 35.00,
                'categoria' => 'pollo',
                'stock' => 0, // Se calcula automáticamente
                'imagen' => null,
            ],
            
            // Refrescos (por unidades)
            [
                'nombre' => 'Coca Cola 500ml',
                'descripcion' => 'Refresco Coca Cola de 500ml bien frío',
                'precio' => 8.00,
                'categoria' => 'refresco',
                'stock' => 20, // Unidades
                'imagen' => null,
            ],
            [
                'nombre' => 'Fanta 500ml',
                'descripcion' => 'Refresco Fanta naranja de 500ml',
                'precio' => 8.00,
                'categoria' => 'refresco',
                'stock' => 18, // Unidades
                'imagen' => null,
            ],
            [
                'nombre' => 'Sprite 500ml',
                'descripcion' => 'Refresco Sprite de 500ml',
                'precio' => 8.00,
                'categoria' => 'refresco',
                'stock' => 16, // Unidades
                'imagen' => null,
            ],
            [
                'nombre' => 'Agua Mineral 600ml',
                'descripcion' => 'Agua mineral natural de 600ml',
                'precio' => 5.00,
                'categoria' => 'refresco',
                'stock' => 25, // Unidades
                'imagen' => null,
            ],
        ];

        foreach ($platos as $plato) {
            DB::table('productos')->insert($plato);
        }

        $this->command->info('Platos agregados exitosamente a la base de datos.');
    }
}