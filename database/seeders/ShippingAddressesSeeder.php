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

        if (!$cliente) {
            return;
        }

        ShippingAddress::updateOrCreate(
            [
                'user_id' => $cliente->id,
                'address' => 'Av. Principal 123'
            ],
            [
                'city' => 'Lima',

                'state' => 'Lima',

                'zip_code' => '15001',

                'country' => 'Perú',

                'es_principal' => true,
            ]
        );
    }
}