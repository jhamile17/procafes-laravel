<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('codigo', 'ADMIN')->first();
        $customerRole = Role::where('codigo', 'CUSTOMER')->first();

        User::updateOrCreate(
            [
                'email' => 'admin@procafes.com'
            ],
            [
                'name' => 'Administrador',

                'password' => Hash::make('Admin123*'),

                'phone' => '999999999',

                'document_type' => 'DNI',

                'document_number' => '00000000',

                'role_id' => $adminRole?->id,

                'email_verified_at' => now(),

                'address' => 'Oficina Principal'
            ]
        );

        User::updateOrCreate(
            [
                'email' => 'cliente@procafes.com'
            ],
            [
                'name' => 'Cliente Demo',

                'password' => Hash::make('Cliente123*'),

                'phone' => '988888888',

                'document_type' => 'DNI',

                'document_number' => '11111111',

                'role_id' => $customerRole?->id,

                'email_verified_at' => now(),

                'address' => 'Lima'
            ]
        );
    }
}