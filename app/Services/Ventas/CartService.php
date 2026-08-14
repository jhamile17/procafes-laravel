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
    private const MAX_CANTIDAD = 8;
    private const IGV = 0.18;
    private const ERROR_MAX_CANTIDAD =
    'Solo puedes comprar hasta 8 unidades de este producto.';
    public function __construct(
        protected ProductService $productService,
        protected InventoryService $inventoryService,
        protected RecommendationService $recommendationService,
    ) {
    }

    /*Obtener o crear carrito */

    public function obtenerCarrito(int $userId): Cart
    {
        return Cart::firstOrCreate(
            [
                'user_id' => $userId,
            ],
            [
                'estado'             => true,
                'ultima_actividad'   => now(),
            ]
        );
    }

    /*Agregar producto al carrito*/
    public function agregarProducto(
        int $userId,
        int $productId,
        int $cantidad = 1
    ): CartItem {
         $this->validarCantidad($cantidad);
        return DB::transaction(function () use (
            $userId,
            $productId,
            $cantidad
        ) {

            $cart = $this->obtenerCarrito($userId);

            $product = $this->productService->obtener($productId);

            $item = $cart->items()
                ->with('product')
                ->where('product_id', $product->id)
                ->first();

            if ($item) {

                $this->actualizarCantidad(
                    $item,
                    $item->quantity + $cantidad
                );

            } else {

                $this->inventoryService->validarStock(
                    $product,
                    $cantidad
                );

                $item = $cart->items()->create([

                    'product_id' => $product->id,
                    'quantity'   => $cantidad,
                    'unit_price' => $product->sale_price,
                    'subtotal'   => bcmul(
                        (string) $product->sale_price,
                        (string) $cantidad,
                        2
                    ),
                ]);
            }

            $this->actualizarActividad($cart);

            return $item->fresh('product');
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
        $this->validarCantidad($cantidad);
        $this->inventoryService->validarStock(
            $item->product,
            $cantidad
        );

        $item->quantity = $cantidad;

        $item->unit_price = $item->product->sale_price;

        $item->recalcularSubtotal();

        $item->save();

        $this->actualizarActividad($item->cart);

        return $item->fresh('product');
    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar producto del carrito
    |--------------------------------------------------------------------------
    */

    public function eliminarProducto(CartItem $item): bool
    {
        $cart = $item->cart;

        $resultado = $item->delete();

        $this->actualizarActividad($cart);

        return $resultado;
    }

    /*
    |--------------------------------------------------------------------------
    | Vaciar carrito
    |--------------------------------------------------------------------------
    */

    public function vaciarCarrito(Cart $cart): void
    {
        DB::transaction(function () use ($cart) {

            $cart->items()->delete();

            $this->actualizarActividad($cart);

        });
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
    | Calcular total del carrito
    |--------------------------------------------------------------------------
    */

    public function calcularTotal(Cart $cart): float
    {
        return (float) $cart->items()
            ->sum('subtotal');
    }

    /*
    |--------------------------------------------------------------------------
    | Contar cantidad total de productos
    |--------------------------------------------------------------------------
    */

    public function contarProductos(Cart $cart): int
    {
        return (int) $cart->items()
            ->sum('quantity');
    }

    /*
    |--------------------------------------------------------------------------
    | Verificar si un producto existe en el carrito
    |--------------------------------------------------------------------------
    */

    public function tieneProducto(
        Cart $cart,
        int $productId
    ): bool {

        return $cart->items()
            ->where('product_id', $productId)
            ->exists();
    }
    public function vaciarPorUsuario(
        int $userId
        ): void {

            $cart = $this->obtenerCarrito($userId);

            $this->vaciarCarrito($cart);

        }
    /*
    |--------------------------------------------------------------------------
    | Actualizar última actividad del carrito
    |--------------------------------------------------------------------------
    */

    private function actualizarActividad(Cart $cart): void
    {
        $cart->actualizarActividad();
    }
    private function validarCantidad(int $cantidad): void
        {
        if ($cantidad <= 0) {
            throw new RuntimeException(
                'La cantidad debe ser mayor que cero.'
            );
        }

        if ($cantidad > self::MAX_CANTIDAD) {
            throw new RuntimeException(
                self::ERROR_MAX_CANTIDAD
            );
        }
    }
    /*Calcular resumen del carrito*/

    public function calcularResumen(
        Cart $cart
    ): array {

        $total = round(
            $this->calcularTotal($cart),
            2
        );

        $subtotal = round(
            $total / (1 + self::IGV),
            2
        );

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