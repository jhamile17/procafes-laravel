<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposConsumoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipos_consumo')->insert([
            [
                'codigo' => 'HOT',
                'nombre' => 'Caliente',
                'descripcion' => 'Productos que se consumen calientes.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => 'COLD',
                'nombre' => 'Frío',
                'descripcion' => 'Productos que se consumen fríos.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => 'PACKAGED',
                'nombre' => 'Empacado',
                'descripcion' => 'Productos empacados como café en bolsa o chocolates.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => 'BOTTLED',
                'nombre' => 'Embotellado',
                'descripcion' => 'Productos en botella como licores.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'codigo' => 'ACCESSORY',
                'nombre' => 'Accesorio',
                'descripcion' => 'Artículos y accesorios de PROCafes.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}