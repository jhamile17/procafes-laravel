<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NivelesAlertaStockSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('niveles_alerta_stock')->insert([

            [
                'codigo' => 'LOW',
                'nombre' => 'Stock Bajo',
                'descripcion' => 'El producto alcanzó el stock mínimo.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'codigo' => 'CRITICAL',
                'nombre' => 'Stock Crítico',
                'descripcion' => 'El stock está por agotarse.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'codigo' => 'OUT',
                'nombre' => 'Agotado',
                'descripcion' => 'El producto no tiene existencias.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}