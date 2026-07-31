<?php

declare(strict_types=1);

namespace App\Services\Checkout;

use App\Models\Order;
use App\Services\Pagos\PaymentService;
use App\Services\Pasarelas\MercadoPagoService;
use App\Services\Ventas\CartService;
use App\Services\Ventas\OrderService;
use Illuminate\Support\Facades\DB;
use App\Services\Cliente\AddressService;
use RuntimeException;

class CheckoutService
{
    public function __construct(
        protected CartService $cartService,
        protected OrderService $orderService,
        protected PaymentService $paymentService,
        protected MercadoPagoService $mercadoPagoService,
        protected AddressService $addressService,
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

        $items = $this->cartService->obtenerItems($cart);

        $subTotal = $this->cartService->calcularTotal($cart);

        $igv = round($subTotal * 0.18, 2);

        $total = round($subTotal + $igv, 2);

        return [

            'cart' => $cart,

            'items' => $items,

            'cantidad' => $this->cartService->contarProductos($cart),

            'sub_total' => $subTotal,

            'igv' => $igv,

            'total' => $total,

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Validar checkout
    |--------------------------------------------------------------------------
    */

    public function validar(int $userId): void
    {
        $cart = $this->cartService->obtenerCarrito($userId);

        if ($cart->items()->doesntExist()) {

            throw new RuntimeException(
                'El carrito se encuentra vacío.'
            );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Procesar checkout
    |--------------------------------------------------------------------------
    */

    public function procesar(
        int $userId,
        array $addressData,
        string $deliveryType,
        int $paymentMethodId,
        ?string $observaciones = null,
    ): Order {

        return DB::transaction(function () use (

            $userId,
            $addressData,
            $deliveryType,
            $paymentMethodId,
            $observaciones

        ) {

            /*
            |--------------------------------------------------------------------------
            | Validar carrito
            |--------------------------------------------------------------------------
            */

            $this->validar($userId);
            /*
            |--------------------------------------------------------------------------
            | Obtener carrito
            |--------------------------------------------------------------------------
            */

            $cart = $this->cartService
                ->obtenerCarrito($userId);
            /*
            |--------------------------------------------------------------------------
            | Crear dirección de envío
            |--------------------------------------------------------------------------
            */

            $shippingAddress = $this->addressService
                ->crearDireccion(
                    $userId,
                    [
                        'alias' => 'Dirección de entrega',

                        'direccion' => $addressData['address'],

                        'departamento' => $addressData['state'],

                        'provincia' => $addressData['city'],

                        // Temporalmente utilizaremos la ciudad también como distrito
                        // hasta implementar un selector de Ubigeo.
                        'distrito' => $addressData['city'],

                        'referencia' => $addressData['reference'] ?? null,
                    ]
                );
            /*
            |--------------------------------------------------------------------------
            | Crear pedido
            |--------------------------------------------------------------------------
            */

            $order = $this->orderService
                ->crearPedido(
                    $cart,
                    $shippingAddress->id,
                    $deliveryType,
                    $observaciones
                );
            /*
            |--------------------------------------------------------------------------
            | Crear pago
            |--------------------------------------------------------------------------
            */

            $payment = $this->paymentService
                ->crearPago(
                    $order,
                    $paymentMethodId
                );
            /*
            |--------------------------------------------------------------------------
            | Crear preferencia de Mercado Pago
            |--------------------------------------------------------------------------
            */

            $preference = $this->mercadoPagoService
                ->crearPreferencia($payment);

            /*
            |--------------------------------------------------------------------------
            | Recargar relaciones
            |--------------------------------------------------------------------------
            */

            $order = $order->fresh([

                'items.product',

                'payment.paymentMethod',

                'payment.estadoPago',

            ]);

            /*
            |--------------------------------------------------------------------------
            | URL de Checkout Pro
            |--------------------------------------------------------------------------
            */

            $order->checkout_url = $preference->init_point;

            return $order;

        });

    }
}