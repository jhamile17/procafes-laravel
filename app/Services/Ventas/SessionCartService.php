<?php

declare(strict_types=1);

namespace App\Services\Ventas;

use App\Models\Product;
use App\Services\Inventario\InventoryService;
use Illuminate\Http\Request;
use RuntimeException;

class SessionCartService
{
    private const SESSION_KEY = 'cart';

    private const MAX_CANTIDAD = 8;

    private const IGV = 0.18;

    public function __construct(
        protected InventoryService $inventoryService
    ) {
    }

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

        /*
        |--------------------------------------------------------------------------
        | Máximo por producto
        |--------------------------------------------------------------------------
        */

        if ($cantidad > self::MAX_CANTIDAD) {

            throw new RuntimeException(
                'Solo puedes comprar hasta 8 unidades de este producto.'
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Obtener carrito
        |--------------------------------------------------------------------------
        */

        $cart = $this->obtener($request);

        $id = $product->id;


        /*
        |--------------------------------------------------------------------------
        | Producto ya existente
        |--------------------------------------------------------------------------
        */

        if (isset($cart[$id])) {

            $nuevaCantidad =
                $cart[$id]['quantity'] + $cantidad;


            /*
            |--------------------------------------------------------------------------
            | Validar máximo de 8
            |--------------------------------------------------------------------------
            */

            if ($nuevaCantidad > self::MAX_CANTIDAD) {

                throw new RuntimeException(
                    'Solo puedes comprar hasta 8 unidades de este producto.'
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Validar stock REAL
            |--------------------------------------------------------------------------
            */

            $this->inventoryService->validarStock(
                $product,
                $nuevaCantidad
            );


            /*
            |--------------------------------------------------------------------------
            | Actualizar carrito
            |--------------------------------------------------------------------------
            */

            $cart[$id]['quantity'] =
                $nuevaCantidad;

            $cart[$id]['sub_total'] =
                bcmul(
                    (string) $cart[$id]['unit_price'],
                    (string) $nuevaCantidad,
                    2
                );

        } else {

            /*
            |--------------------------------------------------------------------------
            | Producto nuevo
            |--------------------------------------------------------------------------
            */

            $this->inventoryService->validarStock(
                $product,
                $cantidad
            );


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


        /*
        |--------------------------------------------------------------------------
        | Guardar sesión
        |--------------------------------------------------------------------------
        */

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

        if (!isset($cart[$productId])) {
            return $cart;
        }


        /*
        |--------------------------------------------------------------------------
        | Máximo 8
        |--------------------------------------------------------------------------
        */

        if ($cantidad > self::MAX_CANTIDAD) {

            throw new RuntimeException(
                'Solo puedes comprar hasta 8 unidades de este producto.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Cantidad mínima
        |--------------------------------------------------------------------------
        */

        $cantidad = max(
            1,
            $cantidad
        );


        /*
        |--------------------------------------------------------------------------
        | Obtener producto actual
        |--------------------------------------------------------------------------
        */

        $product = Product::find(
            $productId
        );


        if (!$product) {

            unset(
                $cart[$productId]
            );

            $request->session()->put(
                self::SESSION_KEY,
                $cart
            );

            return $cart;
        }


        /*
        |--------------------------------------------------------------------------
        | Validar stock REAL
        |--------------------------------------------------------------------------
        */

        $this->inventoryService->validarStock(
            $product,
            $cantidad
        );


        /*
        |--------------------------------------------------------------------------
        | Actualizar
        |--------------------------------------------------------------------------
        */

        $cart[$productId]['quantity'] =
            $cantidad;

        $cart[$productId]['unit_price'] =
            $product->sale_price;

        $cart[$productId]['sub_total'] =
            bcmul(
                (string) $product->sale_price,
                (string) $cantidad,
                2
            );


        /*
        |--------------------------------------------------------------------------
        | Actualizar imagen/nombre por si cambiaron
        |--------------------------------------------------------------------------
        */

        $cart[$productId]['name'] =
            $product->name;

        $cart[$productId]['image'] =
            $product->image_url;


        /*
        |--------------------------------------------------------------------------
        | Guardar sesión
        |--------------------------------------------------------------------------
        */

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

        unset(
            $cart[$productId]
        );

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

    public function vaciar(
        Request $request
    ): void {

        $request->session()->forget(
            self::SESSION_KEY
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Calcular total
    |--------------------------------------------------------------------------
    */

    public function total(
        Request $request
    ): float {

        return (float) collect(
            $this->obtener($request)
        )->sum('sub_total');
    }

    /*
    |--------------------------------------------------------------------------
    | Cantidad total de productos
    |--------------------------------------------------------------------------
    */

    public function cantidad(
        Request $request
    ): int {

        return (int) collect(
            $this->obtener($request)
        )->sum('quantity');
    }

    /*
    |--------------------------------------------------------------------------
    | Sincronizar carrito de sesión
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


        $this->vaciar(
            $request
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Calcular resumen
    |--------------------------------------------------------------------------
    */

    public function calcularResumen(
        Request $request
    ): array {

        $subtotal = round(
            $this->total($request),
            2
        );

        $igv = round(
            $subtotal * self::IGV,
            2
        );

        $total = round(
            $subtotal + $igv,
            2
        );

        return [

            'subtotal' => $subtotal,

            'igv' => $igv,

            'total' => $total,

        ];
    }
}