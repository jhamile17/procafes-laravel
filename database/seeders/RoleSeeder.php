<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [

            [
                'codigo' => 'ADMIN',
                'nombre' => 'Administrador',
                'descripcion' => 'Administrador del sistema',
                'estado' => true,
            ],

            [
                'codigo' => 'CUSTOMER',
                'nombre' => 'Cliente',
                'descripcion' => 'Cliente de la tienda',
                'estado' => true,
            ],

        ];

        foreach ($roles as $role) {

            Role::updateOrCreate(
                [
                    'codigo' => $role['codigo'],
                ],
                $role
            );

        }
    }
}