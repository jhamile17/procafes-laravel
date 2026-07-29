<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartsSeeder extends Seeder
{
    public function run(): void
    {
        $cliente = User::where('email', 'cliente@procafes.com')->first();

        if (! $cliente) {
            return;
        }

        Cart::updateOrCreate(
            [
                'user_id' => $cliente->id,
                'estado' => true,
            ],
            [
                'ultima_actividad' => now(),
            ]
        );
    }
}