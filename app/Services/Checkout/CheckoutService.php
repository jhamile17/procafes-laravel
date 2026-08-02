<?php

declare(strict_types=1);

namespace App\Services\Checkout;

use App\Models\Cart;
use App\Models\Order;
use App\Services\Cliente\AddressService;
use App\Services\Pagos\PaymentMethodService;
use App\Services\Pagos\PaymentService;
use App\Services\Ventas\CartService;
use App\Services\Ventas\OrderService;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CheckoutService
{
    public function __construct(
        protected CartService $cartService,
        protected OrderService $orderService,
        protected PaymentService $paymentService,
        protected PaymentMethodService $paymentMethodService,
        protected AddressService $addressService,
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener información del checkout
    |--------------------------------------------------------------------------
    */

    public function obtenerResumen(
        int $userId
    ): array {

        $cart = $this->validar($userId);

        $total = $this->cartService
            ->calcularTotal($cart);

        return [

            'cart' => $cart,

            'items' => $this->cartService
                ->obtenerItems($cart),

            'cantidad' => $this->cartService
                ->contarProductos($cart),

            'subtotal' => round(
                $total / 1.18,
                2
            ),

            'igv' => round(
                $total - ($total / 1.18),
                2
            ),

            'total' => $total,

            'address' => $this->addressService
                ->obtenerDireccion($userId),

            'paymentMethods' => $this->paymentMethodService
                ->obtenerActivos(),

        ];

    }

    /*
    |--------------------------------------------------------------------------
    | Validar carrito
    |--------------------------------------------------------------------------
    */

    public function validar(
        int $userId
    ): Cart {

        $cart = $this->cartService
            ->obtenerCarrito($userId);

        if ($cart->items()->doesntExist()) {

            throw new RuntimeException(
                'El carrito se encuentra vacío.'
            );

        }

        return $cart;

    }

    /*
    |--------------------------------------------------------------------------
    | Procesar checkout
    |--------------------------------------------------------------------------
    */

    public function procesar(
        int $userId,
        array $data
    ): Order {

        return DB::transaction(function () use (
            $userId,
            $data
        ) {

            /*
            |--------------------------------------------------------------------------
            | Validar carrito
            |--------------------------------------------------------------------------
            */

            $cart = $this->validar($userId);

            /*
            |--------------------------------------------------------------------------
            | Guardar dirección
            |--------------------------------------------------------------------------
            */

            $address = $this->addressService
                ->guardar(
                    $userId,
                    $data
                );

            /*
            |--------------------------------------------------------------------------
            | Crear pedido
            |--------------------------------------------------------------------------
            */

            $order = $this->orderService
                ->crearPedido(
                    cart: $cart,
                    shippingAddress: $address,
                );

            /*
            |--------------------------------------------------------------------------
            | Registrar pago
            |--------------------------------------------------------------------------
            */

            $this->paymentService
                ->crearPago(
                    order: $order,
                    paymentMethodId: (int) $data['payment_method_id'],
                );

            /*
            |--------------------------------------------------------------------------
            | Retornar pedido
            |--------------------------------------------------------------------------
            */

            return $order->fresh([

                'shippingAddress',

                'items.product',

                'payment.paymentMethod',

                'payment.estadoPago',

            ]);

        });

    }

}