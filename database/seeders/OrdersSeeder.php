<?php

namespace Database\Seeders;

use App\Models\EstadoPedido;
use App\Models\Order;
use App\Models\ShippingAddress;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrdersSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'cliente@procafes.com')->first();

        if (! $user) {
            return;
        }

        $direccion = ShippingAddress::where('user_id', $user->id)
            ->where('es_principal', true)
            ->first();

        if (! $direccion) {
            return;
        }

        $estado = EstadoPedido::where('codigo', 'PENDING')->first();

        if (! $estado) {
            return;
        }

        Order::updateOrCreate(
            [
                'numero_pedido' => 'PC-2026-000001',
            ],
            [
                'user_id' => $user->id,

                'shipping_address_id' => $direccion->id,

                'estado_pedido_id' => $estado->id,

                /*
                |--------------------------------------------------------------------------
                | Snapshot de la dirección
                |--------------------------------------------------------------------------
                */

                'delivery_alias'         => $direccion->alias,
                'delivery_direccion'     => $direccion->direccion,
                'delivery_departamento'  => $direccion->departamento,
                'delivery_provincia'     => $direccion->provincia,
                'delivery_distrito'      => $direccion->distrito,
                'delivery_referencia'    => $direccion->referencia,

                /*
                |--------------------------------------------------------------------------
                | Pedido
                |--------------------------------------------------------------------------
                */

                'total_price' => 0.00,

                'delivery_type' => 'delivery',

                'observaciones' => 'Pedido de prueba.',
            ]
        );
    }
}