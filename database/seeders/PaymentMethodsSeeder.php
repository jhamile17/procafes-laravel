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
                'nombre' => 'Transferencia Bancaria',
                'descripcion' => 'Transferencia bancaria.',
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nombre' => 'Yape',
                'descripcion' => 'Pago mediante Yape.',
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nombre' => 'Plin',
                'descripcion' => 'Pago mediante Plin.',
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nombre' => 'Culqi',
                'descripcion' => 'Pasarela Culqi.',
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nombre' => 'Stripe',
                'descripcion' => 'Pasarela Stripe.',
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