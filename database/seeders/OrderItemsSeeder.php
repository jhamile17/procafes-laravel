<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderItemsSeeder extends Seeder
{
    public function run(): void
    {
        $order = Order::where('numero_pedido', 'PC-2026-000001')->first();

        if (! $order) {
            return;
        }

        $producto = Product::first();

        if (! $producto) {
            return;
        }

        OrderItem::updateOrCreate(
            [
                'order_id'   => $order->id,
                'product_id' => $producto->id,
            ],
            [
                'quantity'   => 2,
                'unit_price' => $producto->sale_price,
                'subtotal'   => bcmul(
                    (string) $producto->sale_price,
                    '2',
                    2
                ),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Actualizar el total del pedido
        |--------------------------------------------------------------------------
        */

        $order->actualizarTotal();
    }
}