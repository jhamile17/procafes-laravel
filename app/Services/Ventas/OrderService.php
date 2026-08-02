<?php

declare(strict_types=1);

namespace App\Services\Ventas;

use App\Models\Cart;
use App\Models\EstadoPedido;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingAddress;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class OrderService
{
    /*
    |--------------------------------------------------------------------------
    | Crear pedido
    |--------------------------------------------------------------------------
    */

    public function crearPedido(
        Cart $cart,
        ShippingAddress $shippingAddress,
    ): Order {

        $this->validarCarrito($cart);

        return DB::transaction(function () use (
            $cart,
            $shippingAddress
        ) {

            $estadoPendiente = $this->obtenerEstadoPedido(
                'PENDIENTE'
            );

            $order = $this->crearRegistroPedido(
                $cart,
                $shippingAddress,
                $estadoPendiente
            );

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

        ])->findOrFail($orderId);

    }

    /*
    |--------------------------------------------------------------------------
    | Obtener todos
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
    | Obtener pedidos del usuario
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

        if (! $order->estadoPedido->esPendiente()) {

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
    | Cambiar estado
    |--------------------------------------------------------------------------
    */

    private function cambiarEstado(
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
    | Crear registro del pedido
    |--------------------------------------------------------------------------
    */

    private function crearRegistroPedido(
        Cart $cart,
        ShippingAddress $shippingAddress,
        EstadoPedido $estado
    ): Order {

        return Order::create([

            'user_id' => $cart->user_id,

            'shipping_address_id' => $shippingAddress->id,

            'estado_pedido_id' => $estado->id,

            'numero_pedido' => $this->generarNumeroPedido(),

            /*
            |--------------------------------------------------------------------------
            | Snapshot de la dirección
            |--------------------------------------------------------------------------
            */

            'delivery_direccion' => $shippingAddress->direccion,

            'delivery_departamento' => $shippingAddress->departamento,

            'delivery_provincia' => $shippingAddress->provincia,

            'delivery_distrito' => $shippingAddress->distrito,

            'total_price' => 0,

        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Validar carrito
    |--------------------------------------------------------------------------
    */

    private function validarCarrito(
        Cart $cart
    ): void {

        $cart->loadMissing('items.product');

        if (! $cart->estado) {

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

            if (! $item->product) {

                throw new RuntimeException(
                    'Existe un producto inválido dentro del carrito.'
                );

            }

            if (! $item->product->status) {

                throw new RuntimeException(
                    "El producto {$item->product->name} se encuentra inactivo."
                );

            }

            if (! $item->product->isAvailable($item->quantity)) {

                throw new RuntimeException(
                    "Stock insuficiente para {$item->product->name}."
                );

            }

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Crear items
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

                'unit_price' => $item->unit_price,

                'subtotal' => $item->subtotal,

            ]);

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Obtener estado
    |--------------------------------------------------------------------------
    */

    private function obtenerEstadoPedido(
        string $codigo
    ): EstadoPedido {

        return EstadoPedido::query()

            ->whereRaw(
                'UPPER(codigo) = ?',
                [strtoupper($codigo)]
            )

            ->firstOrFail();

    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar total
    |--------------------------------------------------------------------------
    */

    private function actualizarTotalPedido(
        Order $order
    ): void {

        $order->actualizarTotal();

    }

    /*
    |--------------------------------------------------------------------------
    | Generar número
    |--------------------------------------------------------------------------
    */

    private function generarNumeroPedido(): string
    {

        do {

            $numero = 'PED-'
                . now()->format('Ymd')
                . '-'
                . strtoupper(Str::random(6));

        } while (

            Order::where(
                'numero_pedido',
                $numero
            )->exists()

        );

        return $numero;

    }
}