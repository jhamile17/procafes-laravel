<?php

namespace App\Services\Ventas;

use App\Models\Cart;
use App\Models\CartItem;
use App\Services\Catalogo\ProductService;
use App\Services\Inventario\InventoryService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CartService
{
    public function __construct(
        protected ProductService $productService,
        protected InventoryService $inventoryService
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

        if ($cantidad <= 0) {
            throw new RuntimeException(
                'La cantidad debe ser mayor que cero.'
            );
        }

        return DB::transaction(function () use (
            $userId,
            $productId,
            $cantidad
        ) {

            $cart = $this->obtenerCarrito($userId);

            $product = $this->productService->obtener($productId);

            $item = $cart
                ->items()
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

                $item = new CartItem();

                $item->cart_id = $cart->id;

                $item->product_id = $product->id;

                $item->quantity = $cantidad;

                $item->price = $product->sale_price;

                $item->actualizarSubtotal();

                $item->save();

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

        if ($cantidad <= 0) {

            throw new RuntimeException(
                'La cantidad debe ser mayor que cero.'
            );

        }

        $this->inventoryService->validarStock(
            $item->product,
            $cantidad
        );

        $item->quantity = $cantidad;

        $item->price = $item->product->sale_price;

        $item->actualizarSubtotal();

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

    public function obtenerItems(Cart $cart): Collection
    {
        return $cart
            ->items()
            ->with('product')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Calcular total
    |--------------------------------------------------------------------------
    */

    public function calcularTotal(Cart $cart): float
    {
        return (float) $cart
            ->items()
            ->sum('sub_total');
    }

    /*
    |--------------------------------------------------------------------------
    | Contar productos
    |--------------------------------------------------------------------------
    */

    public function contarProductos(Cart $cart): int
    {
        return (int) $cart
            ->items()
            ->sum('quantity');
    }

    /*
    |--------------------------------------------------------------------------
    | Verificar si existe un producto
    |--------------------------------------------------------------------------
    */

    public function tieneProducto(
        Cart $cart,
        int $productId
    ): bool {

        return $cart
            ->items()
            ->where('product_id', $productId)
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar actividad del carrito
    |--------------------------------------------------------------------------
    */

    private function actualizarActividad(Cart $cart): void
    {
        $cart->actualizarActividad();
    }
}