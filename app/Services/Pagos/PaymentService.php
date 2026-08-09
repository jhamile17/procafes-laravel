<?php
declare(strict_types=1);

namespace App\Services\Pagos;

use App\Models\EstadoPago;
use App\Models\Order;
use App\Models\Payment;
use App\Services\Pasarelas\MercadoPagoService;
use App\Services\Ventas\CartService;
use App\Services\Ventas\OrderService;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use RuntimeException;

class PaymentService
{
    public function __construct(
        protected PaymentMethodService $paymentMethodService,
        protected MercadoPagoService $mercadoPagoService,
    ) {
    }

    /*Crear pago*/
    public function crearPago(
        Order $order,
        int $paymentMethodId
    ): Payment {
        $this->validarPedido($order);
        $paymentMethod = $this->paymentMethodService
                ->obtener(
                    $paymentMethodId
                );
            $estadoPendiente = $this->obtenerEstado(
                EstadoPago::PENDING
            );
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method_id' => $paymentMethod->id,
                'estado_pago_id' => $estadoPendiente->id,
                'amount' => $order->total_price,
                'reference' => $this->generarReferencia(),
                'transaction_data' => [],
            ]);
            $payment->load([
                'order',
                'paymentMethod',
                'estadoPago',
            ]);
            return $payment;
    }
    /*Iniciar pago por pedido*/
    public function iniciarPagoPorPedido(
        Order $order
    ): Payment {
        return $this->iniciarPago(
            $this->obtenerPorPedido(
                $order
            )

        );

    }

    /*Iniciar pago*/

    public function iniciarPago(
        Payment $payment
    ): Payment {
        $payment->loadMissing([
            'paymentMethod',
            'order.user',
            'order.items.product',
        ]);

        /*Mercado Pago*/
        if (
            $this->paymentMethodService
                ->esMercadoPago(
                    $payment->paymentMethod
                )
        ) {

            $this->cambiarEstado(
                $payment,
                EstadoPago::PROCESSING
            );
            $preference = $this->mercadoPagoService
                ->crearPreferencia(
                    $payment
                );
            if (

                empty(
                    $preference['preference_id']
                )
            ) {

                throw new RuntimeException(
                    'No fue posible crear la preferencia de Mercado Pago.'
                );
            }
            $payment = $this->actualizarTransaccion(
                payment: $payment,
                transactionData: $preference,
            );
        }
        return $payment;
    }
        /* Obtener pago*/
    public function obtener(
        int $paymentId
    ): Payment {

        return Payment::query()

            ->with([

                'order',

                'paymentMethod',

                'estadoPago',

            ])

            ->findOrFail(
                $paymentId
            );

    }

    /*
    |--------------------------------------------------------------------------
    | Obtener todos los pagos
    |--------------------------------------------------------------------------
    */

    public function obtenerTodos(): Collection
    {

        return Payment::query()

            ->with([

                'order',

                'paymentMethod',

                'estadoPago',

            ])

            ->latest()

            ->get();

    }

    /*
    |--------------------------------------------------------------------------
    | Obtener pago por pedido
    |--------------------------------------------------------------------------
    */

    public function obtenerPorPedido(
        Order $order
    ): Payment {

        return Payment::query()

            ->with([

                'order',

                'paymentMethod',

                'estadoPago',

            ])

            ->where(

                'order_id',

                $order->id

            )

            ->firstOrFail();

    }

    /*
    |--------------------------------------------------------------------------
    | Obtener pago por referencia
    |--------------------------------------------------------------------------
    */

    public function obtenerPorReferencia(
        string $reference
    ): ?Payment {

        return Payment::query()

            ->with([

                'order',

                'paymentMethod',

                'estadoPago',

            ])

            ->where(

                'reference',

                $reference

            )

            ->first();

    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar transacción
    |--------------------------------------------------------------------------
    */

    public function actualizarTransaccion(
        Payment $payment,
        ?string $transactionId = null,
        array $transactionData = []
    ): Payment {
        $data = [];
        /*ID de transacción*/

        if (! empty($transactionId)) {
            $data['transaction_id'] = $transactionId;
        }

        /*Datos de la transacción*/
        if (! empty($transactionData)) {
            $data['transaction_data'] = $transactionData;
        }

        /*Actualizar únicamente si existen cambios*/

        if (! empty($data)) {
            $payment->update($data);
        }
    
        return $payment->refresh();

    }
        /*
    |--------------------------------------------------------------------------
    | Confirmar pago
    |--------------------------------------------------------------------------
    */

    public function confirmarPago(
        Payment $payment,
        ?string $transactionId = null,
        array $transactionData = []
    ): Payment {
        return DB::transaction(function () use (
            $payment,
            $transactionId,
            $transactionData,

        ) {
            $payment = $this->actualizarTransaccion(
                payment: $payment,
                transactionId: $transactionId,
                transactionData: $transactionData,
            );

            $this->cambiarEstado(
                $payment,
                EstadoPago::APPROVED
            );

            return $payment->fresh([
                'order',
                'paymentMethod',
                'estadoPago',
            ]);
        });
    }

    /*Rechazar pago*/

    public function rechazarPago(
        Payment $payment,
        ?string $transactionId = null,
        array $transactionData = []
    ): Payment {

        return DB::transaction(function () use (
            $payment,
            $transactionId,
            $transactionData,

        ) {

            $payment = $this->actualizarTransaccion(
                payment: $payment,

                transactionId: $transactionId,

                transactionData: $transactionData,

            );

            $this->cambiarEstado(
                $payment,
                EstadoPago::REJECTED
            );
            $payment->loadMissing([
                'order',
                'paymentMethod',
                'estadoPago',
            ]);
            return $payment;
        });
    }

    /*Eliminar pago*/

    public function eliminarPago(
        Payment $payment
    ): bool {

        if (! $payment->isPendiente()) {
            throw new RuntimeException(
                'Solo se pueden eliminar pagos pendientes.'
            );
        }

        return $payment->delete();

    }

    /*Validar pedido*/

    private function validarPedido(
        Order $order
    ): void {
        if (! $order->exists) {
            throw new RuntimeException(
                'El pedido no existe.'
            );
        }
        if ($order->payment()->exists()) {
            throw new RuntimeException(
                'El pedido ya tiene un pago registrado.'
            );
        }
        if (! $order->items()->exists()) {
            throw new RuntimeException(
                'El pedido no contiene productos.'
            );
        }
    }

    /*Cambiar estado*/

    private function cambiarEstado(
        Payment $payment,
        string $codigoEstado
    ): void {

        $estado = $this->obtenerEstado(
            $codigoEstado
        );
        $payment->actualizarEstado(
            $estado
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Obtener estado
    |--------------------------------------------------------------------------
    */

    private function obtenerEstado(
        string $codigo
    ): EstadoPago {

        return EstadoPago::query()

            ->activos()

            ->where(

                'codigo',

                strtoupper($codigo)

            )

            ->firstOrFail();

    }

    /*
    |--------------------------------------------------------------------------
    | Generar referencia
    |--------------------------------------------------------------------------
    */

    private function generarReferencia(): string
    {

        do {

            $reference = sprintf(

                'PAY-%s-%s',

                now()->format('YmdHis'),

                strtoupper(

                    \Illuminate\Support\Str::random(6)

                )

            );

        } while (

            Payment::where(

                'reference',

                $reference

            )->exists()

        );

        return $reference;

    }

    /*
    |--------------------------------------------------------------------------
    | Verificar Mercado Pago
    |--------------------------------------------------------------------------
    */

    public function esMercadoPago(
        Payment $payment
    ): bool {

        $payment->loadMissing(
            'paymentMethod'
        );

        return $this->paymentMethodService
            ->esMercadoPago(
                $payment->paymentMethod
            );

    }

    /*
    |--------------------------------------------------------------------------
    | Verificar Pago en tienda
    |--------------------------------------------------------------------------
    */

    public function esPagoEnTienda(
        Payment $payment
    ): bool {

        $payment->loadMissing(
            'paymentMethod'
        );

        return $this->paymentMethodService
            ->esPagoEnTienda(
                $payment->paymentMethod
            );

    }

}

