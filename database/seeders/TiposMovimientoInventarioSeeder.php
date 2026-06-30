<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposMovimientoInventarioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tipos_movimiento_inventario')->insert([

            [
                'codigo' => 'ENTRY',
                'nombre' => 'Entrada',
                'descripcion' => 'Ingreso de productos al inventario.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'codigo' => 'EXIT',
                'nombre' => 'Salida',
                'descripcion' => 'Salida de productos por venta.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'codigo' => 'ADJUSTMENT',
                'nombre' => 'Ajuste',
                'descripcion' => 'Corrección manual del inventario.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'codigo' => 'LOSS',
                'nombre' => 'Merma',
                'descripcion' => 'Productos dañados, vencidos o perdidos.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'codigo' => 'RETURN',
                'nombre' => 'Devolución',
                'descripcion' => 'Ingreso o salida por devolución.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}