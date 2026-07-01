<?php

namespace App\Services\Ventas;

use App\Models\Cart;
use App\Models\EstadoPedido;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class OrderService
{
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        protected CartService $cartService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Crear pedido
    |--------------------------------------------------------------------------
    */

    public function crearPedido(
        Cart $cart,
        int $shippingAddressId,
        string $deliveryType,
        ?string $observaciones = null
    ): Order {

        $this->validarCarrito($cart);

        return DB::transaction(function () use (
            $cart,
            $shippingAddressId,
            $deliveryType,
            $observaciones
        ) {

            $estadoPendiente = $this->obtenerEstadoPedido(
                'PENDIENTE'
            );

            $order = Order::create([

                'user_id' => $cart->user_id,

                'shipping_address_id' => $shippingAddressId,

                'estado_pedido_id' => $estadoPendiente->id,

                'numero_pedido' => $this->generarNumeroPedido(),

                'total_price' => 0,

                'delivery_type' => $deliveryType,

                'observaciones' => $observaciones,

            ]);

            $this->crearItems(
                $order,
                $cart
            );

            $this->actualizarTotalPedido(
                $order
            );

            return $order->fresh([
                'user',
                'shippingAddress',
                'estadoPedido',
                'items.product',
            ]);

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener pedido
    |--------------------------------------------------------------------------
    */

    public function obtener(
        int $orderId
    ): Order {

        return Order::with([

            'user',

            'shippingAddress',

            'estadoPedido',

            'items.product',

            'payment',

        ])

        ->findOrFail($orderId);

    }

    /*
    |--------------------------------------------------------------------------
    | Obtener todos los pedidos
    |--------------------------------------------------------------------------
    */

    public function obtenerTodos(): Collection
    {
        return Order::with([

            'user',

            'shippingAddress',

            'estadoPedido',

            'payment',

        ])

        ->latest()

        ->get();
    }
        /*
    |--------------------------------------------------------------------------
    | Obtener pedidos por usuario
    |--------------------------------------------------------------------------
    */

    public function obtenerPorUsuario(
        int $userId
    ): Collection {

        return Order::with([

            'shippingAddress',

            'estadoPedido',

            'items.product',

            'payment',

        ])

        ->where('user_id', $userId)

        ->latest()

        ->get();

    }

    /*
    |--------------------------------------------------------------------------
    | Cambiar estado del pedido
    |--------------------------------------------------------------------------
    */

    public function cambiarEstado(
        Order $order,
        string $codigoEstado
    ): Order {

        $estado = $this->obtenerEstadoPedido(
            $codigoEstado
        );

        $order->update([

            'estado_pedido_id' => $estado->id,

        ]);

        return $order->fresh([
            'estadoPedido',
        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Confirmar pedido
    |--------------------------------------------------------------------------
    */

    public function confirmarPedido(
        Order $order
    ): Order {

        return $this->cambiarEstado(
            $order,
            'CONFIRMADO'
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Cancelar pedido
    |--------------------------------------------------------------------------
    */

    public function cancelarPedido(
        Order $order
    ): Order {

        if ($order->estadoPedido->esCancelado()) {

            throw new RuntimeException(
                'El pedido ya fue cancelado.'
            );

        }

        return $this->cambiarEstado(
            $order,
            'CANCELADO'
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar pedido
    |--------------------------------------------------------------------------
    */

    public function eliminarPedido(
        Order $order
    ): bool {

        if (!$order->estadoPedido->esPendiente()) {

            throw new RuntimeException(
                'Solo se pueden eliminar pedidos pendientes.'
            );

        }

        return DB::transaction(function () use ($order) {

            $order->items()->delete();

            return $order->delete();

        });

    }
        /*
    |--------------------------------------------------------------------------
    | Validar carrito
    |--------------------------------------------------------------------------
    */

    private function validarCarrito(Cart $cart): void
    {
        $cart->loadMissing('items.product');

        if (!$cart->estado) {
            throw new RuntimeException(
                'El carrito se encuentra inactivo.'
            );
        }

        if ($cart->items->isEmpty()) {
            throw new RuntimeException(
                'El carrito no contiene productos.'
            );
        }

        foreach ($cart->items as $item) {

            if (!$item->product) {
                throw new RuntimeException(
                    'Existe un producto inválido dentro del carrito.'
                );
            }

            if (!$item->product->status) {
                throw new RuntimeException(
                    "El producto {$item->product->name} se encuentra inactivo."
                );
            }

            if (
                !$item->product->isAvailable($item->quantity)
            ) {
                throw new RuntimeException(
                    "Stock insuficiente para {$item->product->name}."
                );
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Crear items del pedido
    |--------------------------------------------------------------------------
    */

    private function crearItems(
        Order $order,
        Cart $cart
    ): void {

        foreach ($cart->items as $item) {

            OrderItem::create([

                'order_id' => $order->id,

                'product_id' => $item->product_id,

                'quantity' => $item->quantity,

                'unit_price' => $item->price,

                'subtotal' => $item->sub_total,

            ]);

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Obtener estado del pedido
    |--------------------------------------------------------------------------
    */

    private function obtenerEstadoPedido(
        string $codigo
    ): EstadoPedido {

        return EstadoPedido::query()

            ->activos()

            ->whereRaw(
                'UPPER(codigo) = ?',
                [strtoupper($codigo)]
            )

            ->firstOrFail();

    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar total del pedido
    |--------------------------------------------------------------------------
    */

    private function actualizarTotalPedido(
        Order $order
    ): void {

        $order->actualizarTotal();

    }

    /*
    |--------------------------------------------------------------------------
    | Generar número de pedido
    |--------------------------------------------------------------------------
    */

    private function generarNumeroPedido(): string
    {
        do {

            $numero = 'PED-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(Str::random(6));

        } while (

            Order::where(
                'numero_pedido',
                $numero
            )->exists()

        );

        return $numero;
    }
}