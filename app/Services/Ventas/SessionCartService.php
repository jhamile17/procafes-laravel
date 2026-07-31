<?php

declare(strict_types=1);

namespace App\Services\Ventas;

use App\Models\Product;
use Illuminate\Http\Request;
use RuntimeException;

class SessionCartService
{
    private const SESSION_KEY = 'cart';

    private const MAX_CANTIDAD = 8;

    /*
    |--------------------------------------------------------------------------
    | Obtener carrito
    |--------------------------------------------------------------------------
    */

    public function obtener(Request $request): array
    {
        return $request->session()->get(
            self::SESSION_KEY,
            []
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Agregar producto
    |--------------------------------------------------------------------------
    */

    public function agregar(
        Request $request,
        Product $product,
        int $cantidad = 1
    ): array {

        if ($cantidad <= 0) {
            $cantidad = 1;
        }

        if ($cantidad > self::MAX_CANTIDAD) {
            throw new RuntimeException(
                'Solo puedes comprar hasta 8 unidades de este producto.'
            );
        }

        $cart = $this->obtener($request);

        $id = $product->id;

        if (isset($cart[$id])) {

            $nuevaCantidad = $cart[$id]['quantity'] + $cantidad;

            if ($nuevaCantidad > self::MAX_CANTIDAD) {
                throw new RuntimeException(
                    'Solo puedes comprar hasta 8 unidades de este producto.'
                );
            }

            $cart[$id]['quantity'] = $nuevaCantidad;

            $cart[$id]['sub_total'] = bcmul(
                (string) $cart[$id]['unit_price'],
                (string) $nuevaCantidad,
                2
            );

        } else {

            $cart[$id] = [

                'product_id' => $product->id,

                'name' => $product->name,

                'unit_price' => $product->sale_price,

                'image' => $product->image_url,

                'quantity' => $cantidad,

                'sub_total' => bcmul(
                    (string) $product->sale_price,
                    (string) $cantidad,
                    2
                ),

            ];

        }

        $request->session()->put(
            self::SESSION_KEY,
            $cart
        );

        return $cart;
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar cantidad
    |--------------------------------------------------------------------------
    */

    public function actualizar(
        Request $request,
        int $productId,
        int $cantidad
    ): array {

        $cart = $this->obtener($request);

        if (! isset($cart[$productId])) {
            return $cart;
        }

        $cantidad = max(
            1,
            min($cantidad, self::MAX_CANTIDAD)
        );

        $cart[$productId]['quantity'] = $cantidad;

        $cart[$productId]['sub_total'] = bcmul(
            (string) $cart[$productId]['unit_price'],
            (string) $cantidad,
            2
        );

        $request->session()->put(
            self::SESSION_KEY,
            $cart
        );

        return $cart;
    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar producto
    |--------------------------------------------------------------------------
    */

    public function eliminar(
        Request $request,
        int $productId
    ): array {

        $cart = $this->obtener($request);

        unset($cart[$productId]);

        $request->session()->put(
            self::SESSION_KEY,
            $cart
        );

        return $cart;
    }

    /*
    |--------------------------------------------------------------------------
    | Vaciar carrito
    |--------------------------------------------------------------------------
    */

    public function vaciar(Request $request): void
    {
        $request->session()->forget(
            self::SESSION_KEY
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Calcular total
    |--------------------------------------------------------------------------
    */

    public function total(Request $request): float
    {
        return (float) collect(
            $this->obtener($request)
        )->sum('sub_total');
    }

    /*
    |--------------------------------------------------------------------------
    | Cantidad total de productos
    |--------------------------------------------------------------------------
    */

    public function cantidad(Request $request): int
    {
        return (int) collect(
            $this->obtener($request)
        )->sum('quantity');
    }

    /*
    |--------------------------------------------------------------------------
    | Sincronizar carrito de sesión con la base de datos
    |--------------------------------------------------------------------------
    */

    public function sincronizar(
        Request $request,
        CartService $cartService,
        int $userId
    ): void {

        $cart = $this->obtener($request);

        if (empty($cart)) {
            return;
        }

        foreach ($cart as $item) {

            $cartService->agregarProducto(
                $userId,
                (int) $item['product_id'],
                (int) $item['quantity']
            );

        }

        $this->vaciar($request);
    }
}