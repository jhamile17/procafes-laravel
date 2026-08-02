<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payment_methods')->insert([

            [
                'nombre' => 'Efectivo',
                'descripcion' => 'Pago en efectivo.',
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Mercado Pago',
                'descripcion' => 'Pasarela Mercado Pago.',
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}