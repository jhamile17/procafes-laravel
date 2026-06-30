<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosPagoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('estados_pago')->insert([

            [
                'codigo' => 'PENDING',
                'nombre' => 'Pendiente',
                'descripcion' => 'Pago pendiente.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'codigo' => 'PROCESSING',
                'nombre' => 'Procesando',
                'descripcion' => 'La pasarela está procesando el pago.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'codigo' => 'APPROVED',
                'nombre' => 'Aprobado',
                'descripcion' => 'Pago aprobado.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'codigo' => 'REJECTED',
                'nombre' => 'Rechazado',
                'descripcion' => 'Pago rechazado.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'codigo' => 'REFUNDED',
                'nombre' => 'Reembolsado',
                'descripcion' => 'Pago reembolsado.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}