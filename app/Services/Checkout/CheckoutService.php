<?php

declare(strict_types=1);

namespace App\Services\Checkout;

use App\Models\Cart;
use App\Models\Order;
use App\Models\ConfiguracionEmpresa;
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

    /*
    |--------------------------------------------------------------------------
    | Obtener información del checkout
    |--------------------------------------------------------------------------
    */

    public function obtenerResumen(int $userId): array
    {
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
            'horarioDisponible' => $this->horarioDisponible(),
            'horaApertura' => $this->horaApertura(),
            'horaCierre' => $this->horaCierre(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Validar carrito
    |--------------------------------------------------------------------------
    */

    public function validar(int $userId): Cart
    {
        $cart = $this->cartService->obtenerCarrito($userId);

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

        /*
        |--------------------------------------------------------------------------
        | Validar horario antes de procesar el pedido
        |--------------------------------------------------------------------------
        */

        $this->validarHorarioAtencion();

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

            $deliveryType = $data['delivery_type'];

            $address = null;

            /*
            |--------------------------------------------------------------------------
            | Validar dirección para delivery
            |--------------------------------------------------------------------------
            */

            if ($deliveryType === 'delivery') {

                $address = $this->addressService
                    ->obtenerDireccion($userId);

                if (! $address) {

                    throw new RuntimeException(
                        'Debes registrar una dirección de envío.'
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Calcular resumen
            |--------------------------------------------------------------------------
            */

            $resumen = $this->cartService
                ->calcularResumen($cart);

            /*
            |--------------------------------------------------------------------------
            | Crear pedido
            |--------------------------------------------------------------------------
            */

            $order = $this->orderService->crearPedido(
                cart: $cart,
                shippingAddress: $address,
                resumen: $resumen,
                deliveryType: $deliveryType,
            );

            /*
            |--------------------------------------------------------------------------
            | Crear comprobante
            |--------------------------------------------------------------------------
            */

            $this->comprobanteService->crear(
                order: $order,
                data: $data,
            );

            /*
            |--------------------------------------------------------------------------
            | Registrar pago
            |--------------------------------------------------------------------------
            */

            $this->paymentService->crearPago(
                order: $order,
                paymentMethodId: (int) $data['payment_method_id'],
            );

            /*
            |--------------------------------------------------------------------------
            | Retornar pedido completo
            |--------------------------------------------------------------------------
            */

            return $order->load([
                'shippingAddress',
                'estadoPedido',
                'items.product',
                'payment.paymentMethod',
                'payment.estadoPago',
                'comprobante.electronicDocument',
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Validar horario de atención
    |--------------------------------------------------------------------------
    */

    protected function validarHorarioAtencion(): void
    {
        $configuracion = ConfiguracionEmpresa::obtener();

        $ahora = now()->format('H:i');

        $apertura = substr(
            (string) $configuracion->hora_apertura,
            0,
            5
        );

        $cierre = substr(
            (string) $configuracion->hora_cierre,
            0,
            5
        );

        if (
            $ahora < $apertura ||
            $ahora >= $cierre
        ) {

            throw new RuntimeException(
                "Nuestro horario de atención es de {$apertura} a {$cierre}. " .
                "Puedes realizar tu pedido dentro de nuestro horario."
            );
        }
    }
    /*
    |--------------------------------------------------------------------------
    | Verificar horario disponible
    |--------------------------------------------------------------------------
    */

    protected function horarioDisponible(): bool
    {
        $configuracion = ConfiguracionEmpresa::obtener();

        $ahora = now()->format('H:i');

        $apertura = substr(
            (string) $configuracion->hora_apertura,
            0,
            5
        );

        $cierre = substr(
            (string) $configuracion->hora_cierre,
            0,
            5
        );

        return $ahora >= $apertura && $ahora < $cierre;
    }

    /*
    |--------------------------------------------------------------------------
    | Hora de apertura
    |--------------------------------------------------------------------------
    */

    protected function horaApertura(): string
    {
        return substr(
            (string) ConfiguracionEmpresa::obtener()->hora_apertura,
            0,
            5
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Hora de cierre
    |--------------------------------------------------------------------------
    */

    protected function horaCierre(): string
    {
        return substr(
            (string) ConfiguracionEmpresa::obtener()->hora_cierre,
            0,
            5
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Verificar si permite delivery
    |--------------------------------------------------------------------------
    */

    protected function permiteEnvio(Cart $cart): bool
    {
        foreach ($cart->items as $item) {

            if ($item->product->soloRecojo()) {

                return false;
            }
        }

        return true;
    }
}