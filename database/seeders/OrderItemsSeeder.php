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
        $order = Order::first();

        if (!$order) {
            return;
        }

        $productos = Product::take(3)->get();

        $total = 0;

        foreach ($productos as $producto) {

            $cantidad = rand(1, 3);

            $subtotal = $producto->sale_price * $cantidad;

            OrderItem::updateOrCreate(

                [
                    'order_id' => $order->id,
                    'product_id' => $producto->id,
                ],

                [
                    'quantity' => $cantidad,

                    'unit_price' => $producto->sale_price,

                    'subtotal' => $subtotal,
                ]

            );

            $total += $subtotal;
        }

        $order->update([
            'total_price' => $total,
        ]);
    }
}