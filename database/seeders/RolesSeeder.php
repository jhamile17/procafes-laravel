<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([

            [
                'codigo'=>'ADMIN',
                'nombre'=>'Administrador',
                'descripcion'=>'Administrador del sistema',
                'estado'=>true,
                'created_at'=>now(),
                'updated_at'=>now(),
            ],

            [
                'codigo'=>'CUSTOMER',
                'nombre'=>'Cliente',
                'descripcion'=>'Cliente de la tienda',
                'estado'=>true,
                'created_at'=>now(),
                'updated_at'=>now(),
            ],

        ]);
    }
}