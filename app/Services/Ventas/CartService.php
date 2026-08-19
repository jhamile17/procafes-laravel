<?php

namespace App\Services\Ventas;

use App\Models\Cart;
use App\Models\CartItem;
use App\Services\Catalogo\ProductService;
use App\Services\Catalogo\RecommendationService;
use App\Services\Inventario\InventoryService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CartService
{
    /*
    |--------------------------------------------------------------------------
    | Límites de compra
    |--------------------------------------------------------------------------
    */

    private const MAX_CANTIDAD_PRODUCTO = 8;

    private const MAX_CANTIDAD_TOTAL = 15;

    private const IGV = 0.18;

    private const ERROR_MAX_CANTIDAD_PRODUCTO =
        'Solo puedes comprar hasta 8 unidades de este producto.';

    private const ERROR_MAX_CANTIDAD_TOTAL =
        'Límite de compra alcanzado. Puedes comprar hasta 15 productos por pedido.';


    public function __construct(
        protected ProductService $productService,
        protected InventoryService $inventoryService,
        protected RecommendationService $recommendationService,
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | Obtener o crear carrito
    |--------------------------------------------------------------------------
    */

    public function obtenerCarrito(int $userId): Cart
    {
        return Cart::firstOrCreate(
            [
                'user_id' => $userId,
            ],
            [
                'estado' => true,
                'ultima_actividad' => now(),
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Agregar producto al carrito
    |--------------------------------------------------------------------------
    */

    public function agregarProducto(
        int $userId,
        int $productId,
        int $cantidad = 1
    ): CartItem {

        $this->validarCantidad(
            $cantidad
        );


        return DB::transaction(function () use (
            $userId,
            $productId,
            $cantidad
        ) {

            $cart =
                $this->obtenerCarrito(
                    $userId
                );


            $product =
                $this->productService->obtener(
                    $productId
                );


            $item =
                $cart->items()
                    ->with('product')
                    ->where(
                        'product_id',
                        $product->id
                    )
                    ->first();


            /*
            |--------------------------------------------------------------------------
            | Producto ya existente
            |--------------------------------------------------------------------------
            */

            if ($item) {

                $nuevaCantidad =
                    $item->quantity
                    + $cantidad;


                /*
                | Máximo 8 del mismo producto
                */

                $this->validarCantidad(
                    $nuevaCantidad
                );


                /*
                | Máximo 10 productos en total
                */

                $this->validarLimiteTotal(
                    $cart,
                    $cantidad
                );


                /*
                | Validar stock real
                */

                $this->inventoryService->validarStock(
                    $product,
                    $nuevaCantidad
                );


                /*
                | Actualizar
                */

                $item->quantity =
                    $nuevaCantidad;

                $item->unit_price =
                    $product->sale_price;

                $item->recalcularSubtotal();

                $item->save();


            } else {

                /*
                |--------------------------------------------------------------------------
                | Producto nuevo
                |--------------------------------------------------------------------------
                */

                $this->validarLimiteTotal(
                    $cart,
                    $cantidad
                );


                /*
                | Validar stock
                */

                $this->inventoryService->validarStock(
                    $product,
                    $cantidad
                );


                $item =
                    $cart->items()->create([

                        'product_id' =>
                            $product->id,

                        'quantity' =>
                            $cantidad,

                        'unit_price' =>
                            $product->sale_price,

                        'subtotal' =>
                            bcmul(
                                (string)
                                $product->sale_price,

                                (string)
                                $cantidad,

                                2
                            ),
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Actualizar actividad
            |--------------------------------------------------------------------------
            */

            $this->actualizarActividad(
                $cart
            );


            return $item->fresh(
                'product'
            );
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Actualizar cantidad
    |--------------------------------------------------------------------------
    */

    public function actualizarCantidad(
        CartItem $item,
        int $cantidad
    ): CartItem {

        $this->validarCantidad(
            $cantidad
        );


        $cart =
            $item->cart;


        $cantidadActual =
            (int) $item->quantity;


        /*
        |--------------------------------------------------------------------------
        | Calcular diferencia
        |--------------------------------------------------------------------------
        */

        $diferencia =
            $cantidad
            - $cantidadActual;


        /*
        |--------------------------------------------------------------------------
        | Si aumenta, validar máximo total
        |--------------------------------------------------------------------------
        */

        if ($diferencia > 0) {

            $this->validarLimiteTotal(
                $cart,
                $diferencia
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Validar stock real
        |--------------------------------------------------------------------------
        */

        $this->inventoryService->validarStock(
            $item->product,
            $cantidad
        );


        /*
        |--------------------------------------------------------------------------
        | Actualizar producto
        |--------------------------------------------------------------------------
        */

        $item->quantity =
            $cantidad;

        $item->unit_price =
            $item->product->sale_price;

        $item->recalcularSubtotal();

        $item->save();


        /*
        |--------------------------------------------------------------------------
        | Actualizar actividad
        |--------------------------------------------------------------------------
        */

        $this->actualizarActividad(
            $cart
        );


        return $item->fresh(
            'product'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Eliminar producto del carrito
    |--------------------------------------------------------------------------
    */

    public function eliminarProducto(
        CartItem $item
    ): bool {

        $cart =
            $item->cart;


        $resultado =
            $item->delete();


        $this->actualizarActividad(
            $cart
        );


        return $resultado;
    }


    /*
    |--------------------------------------------------------------------------
    | Vaciar carrito
    |--------------------------------------------------------------------------
    */

    public function vaciarCarrito(
        Cart $cart
    ): void {

        DB::transaction(
            function () use ($cart) {

                $cart->items()->delete();

                $this->actualizarActividad(
                    $cart
                );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Obtener productos del carrito
    |--------------------------------------------------------------------------
    */

    public function obtenerItems(
        Cart $cart
    ): Collection {

        return $cart->items()
            ->with('product')
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Calcular total
    |--------------------------------------------------------------------------
    */

    public function calcularTotal(
        Cart $cart
    ): float {

        return (float) $cart->items()
            ->sum('subtotal');
    }


    /*
    |--------------------------------------------------------------------------
    | Contar cantidad total de productos
    |--------------------------------------------------------------------------
    */

    public function contarProductos(
        Cart $cart
    ): int {

        return (int) $cart->items()
            ->sum('quantity');
    }


    /*
    |--------------------------------------------------------------------------
    | Verificar si un producto existe
    |--------------------------------------------------------------------------
    */

    public function tieneProducto(
        Cart $cart,
        int $productId
    ): bool {

        return $cart->items()
            ->where(
                'product_id',
                $productId
            )
            ->exists();
    }


    /*
    |--------------------------------------------------------------------------
    | Vaciar carrito por usuario
    |--------------------------------------------------------------------------
    */

    public function vaciarPorUsuario(
        int $userId
    ): void {

        $cart =
            $this->obtenerCarrito(
                $userId
            );


        $this->vaciarCarrito(
            $cart
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Actualizar última actividad
    |--------------------------------------------------------------------------
    */

    private function actualizarActividad(
        Cart $cart
    ): void {

        $cart->actualizarActividad();
    }


    /*
    |--------------------------------------------------------------------------
    | Validar cantidad por producto
    |--------------------------------------------------------------------------
    */

    private function validarCantidad(
        int $cantidad
    ): void {

        if ($cantidad <= 0) {

            throw new RuntimeException(
                'La cantidad debe ser mayor que cero.'
            );
        }


        if (
            $cantidad >
            self::MAX_CANTIDAD_PRODUCTO
        ) {

            throw new RuntimeException(
                self::ERROR_MAX_CANTIDAD_PRODUCTO
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Validar límite total del carrito
    |--------------------------------------------------------------------------
    */

    private function validarLimiteTotal(
        Cart $cart,
        int $cantidadAgregar
    ): void {

        $cantidadActual =
            $this->contarProductos(
                $cart
            );


        $nuevaCantidadTotal =
            $cantidadActual
            + $cantidadAgregar;


        if (
            $nuevaCantidadTotal >
            self::MAX_CANTIDAD_TOTAL
        ) {

            throw new RuntimeException(
                self::ERROR_MAX_CANTIDAD_TOTAL
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Calcular resumen
    |--------------------------------------------------------------------------
    */

    public function calcularResumen(
        Cart $cart
    ): array {

        $total =
            round(
                $this->calcularTotal(
                    $cart
                ),
                2
            );


        $subtotal =
            round(
                $total / (1 + self::IGV),
                2
            );


        $igv =
            round(
                $total - $subtotal,
                2
            );


        return [

            'subtotal' =>
                $subtotal,

            'igv' =>
                $igv,

            'total' =>
                $total,
        ];
    }
}