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

    /*
    |--------------------------------------------------------------------------
    | Límites de compra
    |--------------------------------------------------------------------------
    */

    private const MAX_CANTIDAD_PRODUCTO = 8;

    private const MAX_CANTIDAD_TOTAL = 10;

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
    | Cantidad total actual del carrito
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
    | Validar límite total del carrito
    |--------------------------------------------------------------------------
    */

    private function validarCantidadTotal(
        Request $request,
        int $cantidadAgregar
    ): void {

        $cantidadActual =
            $this->cantidad($request);

        $nuevaCantidadTotal =
            $cantidadActual + $cantidadAgregar;

        if (
            $nuevaCantidadTotal >
            self::MAX_CANTIDAD_TOTAL
        ) {

            throw new RuntimeException(
                'Límite de compra alcanzado. Puedes comprar hasta 10 productos por pedido.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Validar límite individual del producto
    |--------------------------------------------------------------------------
    */

    private function validarCantidadProducto(
        int $cantidad
    ): void {

        if (
            $cantidad >
            self::MAX_CANTIDAD_PRODUCTO
        ) {

            throw new RuntimeException(
                'Solo puedes comprar hasta 8 unidades de este producto.'
            );
        }
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
        | Validar máximo por producto
        |--------------------------------------------------------------------------
        */

        $this->validarCantidadProducto(
            $cantidad
        );

        $cart = $this->obtener($request);

        $id = $product->id;

        /*
        |--------------------------------------------------------------------------
        | Producto ya existente
        |--------------------------------------------------------------------------
        */

        if (isset($cart[$id])) {

            $nuevaCantidad =
                (int) $cart[$id]['quantity']
                + $cantidad;

            /*
            | Máximo 8 del mismo producto
            */

            $this->validarCantidadProducto(
                $nuevaCantidad
            );

            /*
            | Máximo 10 productos en total
            */

            $this->validarCantidadTotal(
                $request,
                $cantidad
            );

            /*
            | Validar stock real
            */

            $this->inventoryService->validarStock(
                $product,
                $nuevaCantidad
            );

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
            | Validar máximo total
            |--------------------------------------------------------------------------
            */

            $this->validarCantidadTotal(
                $request,
                $cantidad
            );

            /*
            |--------------------------------------------------------------------------
            | Validar stock real
            |--------------------------------------------------------------------------
            */

            $this->inventoryService->validarStock(
                $product,
                $cantidad
            );

            /*
            |--------------------------------------------------------------------------
            | Crear producto en sesión
            |--------------------------------------------------------------------------
            */

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
        | Guardar carrito
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
        | No permitir valores menores a 1
        |--------------------------------------------------------------------------
        */

        $cantidad = max(
            1,
            $cantidad
        );

        /*
        |--------------------------------------------------------------------------
        | Máximo 8 del mismo producto
        |--------------------------------------------------------------------------
        */

        $this->validarCantidadProducto(
            $cantidad
        );

        /*
        |--------------------------------------------------------------------------
        | Calcular diferencia
        |--------------------------------------------------------------------------
        */

        $cantidadActual =
            (int) $cart[$productId]['quantity'];

        $diferencia =
            $cantidad - $cantidadActual;

        /*
        |--------------------------------------------------------------------------
        | Si estamos aumentando, validar máximo total
        |--------------------------------------------------------------------------
        */

        if ($diferencia > 0) {

            $this->validarCantidadTotal(
                $request,
                $diferencia
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Obtener producto actualizado
        |--------------------------------------------------------------------------
        */

        $product =
            Product::findOrFail(
                $productId
            );

        /*
        |--------------------------------------------------------------------------
        | Validar stock real
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

        /*
        | Actualizar precio por si cambió
        */

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
        | Guardar
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

        $cart =
            $this->obtener($request);

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
    | Sincronizar carrito de sesión
    |--------------------------------------------------------------------------
    */

    public function sincronizar(
        Request $request,
        CartService $cartService,
        int $userId
    ): void {

        $cart =
            $this->obtener($request);

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

    public function calcularResumen(
        Request $request
    ): array {
    
        /*
        |--------------------------------------------------------------------------
        | Total de venta
        |--------------------------------------------------------------------------
        |
        | El sale_price ya contiene IGV.
        |
        */
    
        $total = round(
            $this->total($request),
            2
        );
    
    
        /*
        |--------------------------------------------------------------------------
        | Obtener subtotal sin IGV
        |--------------------------------------------------------------------------
        */
    
        $subtotal = round(
            $total / (1 + self::IGV),
            2
        );
    
    
        /*
        |--------------------------------------------------------------------------
        | Obtener IGV incluido
        |--------------------------------------------------------------------------
        */
    
        $igv = round(
            $total - $subtotal,
            2
        );
    
    
        return [
    
            'subtotal' => $subtotal,
    
            'igv' => $igv,
    
            'total' => $total,
    
        ];
    }
}