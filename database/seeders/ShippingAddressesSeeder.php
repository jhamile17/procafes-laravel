<?php

namespace Database\Seeders;

use App\Models\ShippingAddress;
use App\Models\User;
use Illuminate\Database\Seeder;

class ShippingAddressesSeeder extends Seeder
{
    public function run(): void
    {
        $cliente = User::where('email', 'cliente@procafes.com')->first();

        if (! $cliente) {
            return;
        }

        ShippingAddress::updateOrCreate(
            [
                'user_id'   => $cliente->id,
                'direccion' => 'Av. Principal 123',
            ],
            [
                'alias'          => 'Casa',

                'direccion'      => 'Av. Principal 123',

                'departamento'   => 'Lima',

                'provincia'      => 'Lima',

                'distrito'       => 'Lima',

                'referencia'     => 'Frente al parque',

                /*
                |--------------------------------------------------------------------------
                | Coordenadas (LocationIQ)
                |--------------------------------------------------------------------------
                */

                'latitude'       => -12.046374,

                'longitude'      => -77.042793,

                /*
                |--------------------------------------------------------------------------
                | Configuración
                |--------------------------------------------------------------------------
                */

                'es_principal'   => true,
            ]
        );
    }
}