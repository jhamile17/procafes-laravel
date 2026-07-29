<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CartItemsSeeder extends Seeder
{
    public function run(): void
    {
        $cart = Cart::where('estado', true)->first();

        if (! $cart) {
            return;
        }

        $producto = Product::first();

        if (! $producto) {
            return;
        }

        CartItem::updateOrCreate(
            [
                'cart_id'    => $cart->id,
                'product_id' => $producto->id,
            ],
            [
                'quantity'   => 2,

                /*
                |--------------------------------------------------------------------------
                | Precio al momento de agregar al carrito
                |--------------------------------------------------------------------------
                */

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
        | Actualizar la actividad del carrito
        |--------------------------------------------------------------------------
        */

        $cart->actualizarActividad();
    }
}