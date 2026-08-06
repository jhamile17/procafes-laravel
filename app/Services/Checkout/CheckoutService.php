<?php

declare(strict_types=1);

namespace App\Services\Checkout;

use App\Models\Cart;
use App\Models\Order;
use App\Services\Cliente\AddressService;
use App\Services\Pagos\PaymentService;
use App\Services\Ventas\CartService;
use App\Services\Ventas\OrderService;
use App\Services\Facturacion\ComprobanteService;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CheckoutService
{
    public function __construct(
        protected CartService $cartService,
        protected OrderService $orderService,
        protected PaymentService $paymentService,
        protected AddressService $addressService,
        protected ComprobanteService $comprobanteservice
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

        $cart = $this->validar(
            $userId
        );
        $resumen = $this->cartService
            ->calcularResumen($cart);
        return [

            'cart' => $cart,

            'items' => $this->cartService
                ->obtenerItems($cart),

            'cantidad' => $this->cartService
                ->contarProductos($cart),

            'subtotal' => $resumen['subtotal'],

            'igv' => $resumen['igv'],

            'total' => $resumen['total'],

            'address' => $this->addressService
                ->obtenerDireccion($userId),

            'permiteEnvio' => $this->permiteEnvio($cart),

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

            $cart = $this->validar(
                $userId
            );

            /*
            |--------------------------------------------------------------------------
            | Obtener dirección registrada
            |--------------------------------------------------------------------------
            */

            $address = $this->addressService
                ->obtenerDireccion(
                    $userId
                );

            if (! $address) {

                throw new RuntimeException(
                    'Debes registrar una dirección de envío.'
                );

            }

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
            /*crear comprobante */
                $this->comprobanteService
                ->crear(

                    order: $order,

                    data: [

                        'tipo_comprobante' => $data['tipo_comprobante'],

                        'tipo_documento' => $data['tipo_documento'],

                        'numero_documento' => $data['numero_documento'],

                        'nombre' => $data['nombre'] ?? null,

                        'razon_social' => $data['razon_social'] ?? null,

                        'direccion_fiscal' => $data['direccion_fiscal'],

                    ],

                );

            /*
            |--------------------------------------------------------------------------
            | Registrar pago
            |--------------------------------------------------------------------------
            */

            $this->paymentService
                ->crearPago(

                    order: $order,

                    paymentMethodCode: $data['payment_method'],

                );

            /*
            |--------------------------------------------------------------------------
            | Cargar relaciones
            |--------------------------------------------------------------------------
            */

            $order->load([

                'shippingAddress',

                'items.product',

                'payment.paymentMethod',

                'payment.estadoPago',

            ]);

            return $order;

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Validar método de entrega
    |--------------------------------------------------------------------------
    */

    protected function permiteEnvio(
        Cart $cart
    ): bool {

        foreach ($cart->items as $item) {

            if ($item->product->soloRecojo()) {

                return false;

            }

        }

        return true;

    }
}