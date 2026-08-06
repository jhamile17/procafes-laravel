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

                'codigo' => 'store',

                'nombre' => 'Pago en tienda',

                'descripcion' => 'El cliente paga al recoger su pedido.',

                'estado' => true,

                'created_at' => now(),

                'updated_at' => now(),

            ],

            [

                'codigo' => 'mercadopago',

                'nombre' => 'Mercado Pago',

                'descripcion' => 'Pago seguro mediante Mercado Pago.',

                'estado' => true,

                'created_at' => now(),

                'updated_at' => now(),

            ],

        ]);
    }
}