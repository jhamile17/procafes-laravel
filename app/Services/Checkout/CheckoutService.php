<?php

declare(strict_types=1);
namespace App\Services\Checkout;
use App\Models\Order;
use App\Services\Pagos\PaymentService;
use App\Services\Pasarelas\IzipayService;
use App\Services\Ventas\CartService;
use App\Services\Ventas\OrderService;
use Illuminate\Support\Facades\DB;

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
    Resumen
    */
    public function obtenerResumen(int $userId): array
    {
        $cart = $this->cartService->obtenerCarrito($userId);

        return [

            'cart' => $cart,

            'items' => $this->cartService->obtenerItems($cart),

            'total' => $this->cartService->calcularTotal($cart),

            'cantidad' => $this->cartService->contarProductos($cart),

        ];
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

            $this->izipayService
                ->crearTransaccion($payment);

            return $order->fresh([
                'items.product',
                'payment',
            ]);

        });
    }
}