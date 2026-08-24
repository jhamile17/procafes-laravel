<?php

declare(strict_types=1);

namespace App\Services\Checkout;

use App\Models\Cart;
use App\Models\Order;
use App\Services\Cliente\AddressService;
use App\Services\Facturacion\ComprobanteService;
use App\Services\Pagos\PaymentService;
use App\Services\Sistema\ConfiguracionEmpresaService;
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
        protected AddressService $addressService,
        protected ComprobanteService $comprobanteService,
        protected ConfiguracionEmpresaService $configuracionService,
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

        $resumen = $this->cartService
            ->calcularResumen($cart);

        return [
            'cart' => $cart,

            'items' =>
                $this->cartService->obtenerItems($cart),

            'cantidad' =>
                $this->cartService->contarProductos($cart),

            'subtotal' =>
                $resumen['subtotal'],

            'igv' =>
                $resumen['igv'],

            'total' =>
                $resumen['total'],

            'address' =>
                $this->addressService
                    ->obtenerDireccion($userId),

            'permiteEnvio' =>
                $this->permiteEnvio($cart),

            /*
            |--------------------------------------------------------------------------
            | Horario
            |--------------------------------------------------------------------------
            */

            'horarioDisponible' =>
                $this->horarioDisponible(),

            'horaApertura' =>
                $this->horaApertura(),

            'horaCierre' =>
                $this->horaCierre(),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Validar carrito
    |--------------------------------------------------------------------------
    */

    public function validar(int $userId): Cart
    {
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

        /*
        |--------------------------------------------------------------------------
        | Validar horario antes de crear el pedido
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

            $deliveryType =
                $data['delivery_type'];

            $address = null;


            /*
            |--------------------------------------------------------------------------
            | Validar dirección para delivery
            |--------------------------------------------------------------------------
            */

            if ($deliveryType === 'delivery') {

                $address =
                    $this->addressService
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

            $resumen =
                $this->cartService
                    ->calcularResumen($cart);


            /*
            |--------------------------------------------------------------------------
            | Crear pedido
            |--------------------------------------------------------------------------
            */

            $order =
                $this->orderService->crearPedido(
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
                paymentMethodId:
                    (int) $data['payment_method_id'],
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
        $configuracion = $this->configuracionService->obtener();

        /*
        |--------------------------------------------------------------------------
        | Obtener el día actual
        |--------------------------------------------------------------------------
        */

        $diaActual = strtolower(
            now()->locale('es')->dayName
        );

        /*
        |--------------------------------------------------------------------------
        | Buscar horario del día
        |--------------------------------------------------------------------------
        */

        $horario = $configuracion->horarios
            ->firstWhere('dia', $diaActual);

        /*
        |--------------------------------------------------------------------------
        | Si no existe horario o el día está cerrado
        |--------------------------------------------------------------------------
        */

        if (! $horario || ! $horario->activo) {

            throw new RuntimeException(
                'PROCÁFES se encuentra cerrado el día de hoy. ' .
                'Puedes realizar tu pedido dentro de nuestro horario de atención.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Hora actual
        |--------------------------------------------------------------------------
        */

        $ahora = now()->format('H:i');

        $apertura = substr(
            (string) $horario->hora_apertura,
            0,
            5
        );

        $cierre = substr(
            (string) $horario->hora_cierre,
            0,
            5
        );

        /*
        |--------------------------------------------------------------------------
        | Validar horario
        |--------------------------------------------------------------------------
        */

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
        $configuracion = $this->configuracionService->obtener();

        /*
        |--------------------------------------------------------------------------
        | Día actual
        |--------------------------------------------------------------------------
        */

        $diaActual = strtolower(
            now()->locale('es')->dayName
        );

        /*
        |--------------------------------------------------------------------------
        | Buscar horario
        |--------------------------------------------------------------------------
        */

        $horario = $configuracion->horarios
            ->firstWhere('dia', $diaActual);

        /*
        |--------------------------------------------------------------------------
        | Si no existe o está cerrado
        |--------------------------------------------------------------------------
        */

        if (! $horario || ! $horario->activo) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Horario del día
        |--------------------------------------------------------------------------
        */

        $ahora = now()->format('H:i');

        $apertura = substr(
            (string) $horario->hora_apertura,
            0,
            5
        );

        $cierre = substr(
            (string) $horario->hora_cierre,
            0,
            5
        );

        return
            $ahora >= $apertura &&
            $ahora < $cierre;
    }


    /*
    |--------------------------------------------------------------------------
    | Hora de apertura para mostrar
    |--------------------------------------------------------------------------
    */

    protected function horaApertura(): string
    {
        $configuracion = $this->configuracionService->obtener();

        $diaActual = strtolower(
            now()->locale('es')->dayName
        );

        $horario = $configuracion->horarios
            ->firstWhere('dia', $diaActual);

        /*
        |--------------------------------------------------------------------------
        | Si el día está cerrado
        |--------------------------------------------------------------------------
        */

        if (! $horario || ! $horario->activo) {
            return '--';
        }

        return \Carbon\Carbon::parse(
            $horario->hora_apertura
        )->format('g:i a');
    }


    /*
    |--------------------------------------------------------------------------
    | Hora de cierre para mostrar
    |--------------------------------------------------------------------------
    */

    protected function horaCierre(): string
    {
        $configuracion = $this->configuracionService->obtener();

        $diaActual = strtolower(
            now()->locale('es')->dayName
        );

        $horario = $configuracion->horarios
            ->firstWhere('dia', $diaActual);

        /*
        |--------------------------------------------------------------------------
        | Si el día está cerrado
        |--------------------------------------------------------------------------
        */

        if (! $horario || ! $horario->activo) {
            return '--';
        }

        return \Carbon\Carbon::parse(
            $horario->hora_cierre
        )->format('g:i a');
    }

    /*
    |--------------------------------------------------------------------------
    | Verificar si permite delivery
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