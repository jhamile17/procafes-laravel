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
        $user = User::where('role', User::ROLE_CUSTOMER)->first();

        if (!$user) {
            return;
        }

        $direccion = ShippingAddress::where('user_id', $user->id)->first();

        if (!$direccion) {
            return;
        }

        $estado = EstadoPedido::where('codigo', 'PENDING')->first();

        if (!$estado) {
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

                'total_price' => 0,

                'delivery_type' => 'DELIVERY',

                'observaciones' => 'Pedido de prueba.',

            ]

        );
    }
}