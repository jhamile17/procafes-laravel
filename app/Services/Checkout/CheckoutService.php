<?php

declare(strict_types=1);

namespace App\Services\Checkout;

use App\Models\Order;
use App\Services\Pagos\PaymentService;
use App\Services\Pasarelas\IzipayService;
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
        protected IzipayService $izipayService,
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener resumen del checkout
    |--------------------------------------------------------------------------
    */

    public function obtenerResumen(int $userId): array
    {
        $cart = $this->cartService->obtenerCarrito($userId);

        return [

            'cart' => $cart,

            'items' => $this->cartService
                ->obtenerItems($cart),

            'total' => $this->cartService
                ->calcularTotal($cart),

            'cantidad' => $this->cartService
                ->contarProductos($cart),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Validar Checkout
    |--------------------------------------------------------------------------
    */

    public function validar(int $userId): void
    {
        $cart = $this->cartService
            ->obtenerCarrito($userId);

        if ($cart->items()->doesntExist()) {

            throw new RuntimeException(
                'El carrito se encuentra vacío.'
            );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Procesar Checkout
    |--------------------------------------------------------------------------
    */

    public function procesar(
        int $userId,
        int $shippingAddressId,
        string $deliveryType,
        int $paymentMethodId,
        ?string $observaciones = null,
    ): Order {

        return DB::transaction(function () use (

            $userId,
            $shippingAddressId,
            $deliveryType,
            $paymentMethodId,
            $observaciones

        ) {

            $this->validar($userId);

            $cart = $this->cartService
                ->obtenerCarrito($userId);

            $order = $this->orderService
                ->crearPedido(
                    $cart,
                    $shippingAddressId,
                    $deliveryType,
                    $observaciones
                );

            $payment = $this->paymentService
                ->crearPago(
                    $order,
                    $paymentMethodId
                );

            /*
            |--------------------------------------------------------------------------
            | Pasarela Izipay
            |--------------------------------------------------------------------------
            |
            | Cuando se integre la API aquí se generará
            | el token, la URL de pago o la transacción.
            |
            */

            $this->izipayService
                ->crearTransaccion($payment);

            return $order->fresh([
                'items.product',
                'payment.paymentMethod',
                'payment.estadoPago',
            ]);

        });
    }
}