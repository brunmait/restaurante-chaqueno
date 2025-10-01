<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar stock existente
        DB::table('stock')->delete();
        
        // Obtener IDs de los insumos
        $chanchoId = DB::table('productos')->where('nombre', 'Carne de chancho')->value('id');
        $polloId = DB::table('productos')->where('nombre', 'Pollo entero')->value('id');
        
        if ($chanchoId) {
            // Agregar 3 kilos de carne de chancho
            DB::table('stock')->insert([
                'producto_id' => $chanchoId,
                'cantidad' => 3,
                'tipo' => 'entrada',
                'fecha' => now(),
            ]);
        }
        
        if ($polloId) {
            // Agregar 5 pollos enteros
            DB::table('stock')->insert([
                'producto_id' => $polloId,
                'cantidad' => 5,
                'tipo' => 'entrada',
                'fecha' => now(),
            ]);
        }
        
        $this->command->info('Stock de ejemplo agregado: 3kg de chancho y 5 pollos');
    }
}
