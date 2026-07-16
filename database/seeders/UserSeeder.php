<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('codigo', 'ADMIN')->firstOrFail();
        $customerRole = Role::where('codigo', 'CUSTOMER')->firstOrFail();

        User::updateOrCreate(
            [
                'email' => 'procafes3@gmail.com',
            ],
            [
                'role_id' => $adminRole->id,

                'name' => 'Administrador',

                'nombres' => 'Administrador',
                'apellido_paterno' => 'Sistema',
                'apellido_materno' => 'PROCAFES',

                'tipo_documento' => 'DNI',
                'numero_documento' => '00000000',

                'password' => Hash::make('Admin123*'),

                'provider' => User::PROVIDER_LOCAL,
                'provider_id' => null,

                'celular' => '999999999',
                'direccion' => 'Oficina Principal',

                'estado' => true,

                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            [
                'email' => 'cliente@procafes.com',
            ],
            [
                'role_id' => $customerRole->id,

                'name' => 'Cliente Demo',

                'nombres' => 'Cliente',
                'apellido_paterno' => 'Demo',
                'apellido_materno' => 'PROCAFES',

                'tipo_documento' => 'DNI',
                'numero_documento' => '11111111',

                'password' => Hash::make('Cliente123*'),

                'provider' => User::PROVIDER_LOCAL,
                'provider_id' => null,

                'celular' => '988888888',
                'direccion' => 'Lima',

                'estado' => true,

                'email_verified_at' => now(),
            ]
        );
    }
}