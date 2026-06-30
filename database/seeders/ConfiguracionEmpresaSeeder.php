<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfiguracionEmpresaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('configuracion_empresa')->insert([

            'nombre_empresa' => 'PROCAFES',

            'ruc' => '00000000000',

            'correo' => 'info@procafes.com',

            'telefono' => '999999999',

            'direccion' => 'Dirección de la empresa',

            'logo' => null,

            'facebook' => null,

            'instagram' => null,

            'tiktok' => null,

            'created_at' => now(),

            'updated_at' => now(),

        ]);
    }
}