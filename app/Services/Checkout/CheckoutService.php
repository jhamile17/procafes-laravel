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
        protected ComprobanteService $comprobanteService
    ) {
    }

    /*Obtener información del checkout*/

    public function obtenerResumen(int $userId): array {
        $cart = $this->validar($userId);
        $resumen = $this->cartService->calcularResumen($cart);
        return [
            'cart' => $cart,
            'items' => $this->cartService->obtenerItems($cart),
            'cantidad' => $this->cartService->contarProductos($cart),
            'subtotal' => $resumen['subtotal'],
            'igv' => $resumen['igv'],
            'total' => $resumen['total'],
            'address' => $this->addressService->obtenerDireccion($userId),
            'permiteEnvio' => $this->permiteEnvio($cart),
        ];
    }

    /*Validar carrito*/
    public function validar(int $userId): Cart {
        $cart = $this->cartService->obtenerCarrito($userId);
        if ($cart->items()->doesntExist()) {
            throw new RuntimeException(
                'El carrito se encuentra vacío.'
            );
        }
        return $cart;
    }

    /*Procesar checkout*/

    public function procesar(
        int $userId,
        array $data
    ): Order {
        return DB::transaction(function () use ($userId,$data) {

            /*Validar carrito*/
            $cart = $this->validar($userId);

            /*Obtener dirección registrada*/
            $address = $this->addressService
                ->obtenerDireccion(
                    $userId
                );

            if (! $address) {

                throw new RuntimeException(
                    'Debes registrar una dirección de envío.'
                );

            }
            $resumen = $this->cartService->calcularResumen($cart);
            /*Crear pedido*/
            $order = $this->orderService->crearPedido(

                    cart: $cart,
                    shippingAddress: $address,
                    resumen: $resumen,

                );
            /*crear comprobante */
                $this->comprobanteService->crear(
                    order: $order,
                    data: $data,);
                
            /*Registrar pago*/

                $this->paymentService->crearPago(

                    order: $order,
                    paymentMethodCode: $data['payment_method'],

                );

                return $order->load([
                    'shippingAddress',
                    'estadoPedido',
                    'items.product',
                    'payment.paymentMethod',
                    'payment.estadoPago',
                    'comprobante',
                    'electronicDocument',
                    
                ]);
        });
    }

    protected function permiteEnvio(Cart $cart): bool {

        foreach ($cart->items as $item) {
            if ($item->product->soloRecojo()) {
                return false;
            }
        }
        return true;
    }
}