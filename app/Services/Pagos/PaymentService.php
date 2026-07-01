<?php

namespace App\Services\Pagos;

use App\Models\EstadoPago;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Services\Ventas\OrderService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class PaymentService
{
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        protected OrderService $orderService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Crear pago
    |--------------------------------------------------------------------------
    */

    public function crearPago(
        Order $order,
        int $paymentMethodId
    ): Payment {

        $this->validarPedido($order);

        $paymentMethod = $this->validarMetodoPago(
            $paymentMethodId
        );

        return DB::transaction(function () use (
            $order,
            $paymentMethod
        ) {

            $estadoPendiente = $this->obtenerEstadoPago(
                'PENDIENTE'
            );

            $payment = Payment::create([

                'order_id' => $order->id,

                'payment_method_id' => $paymentMethod->id,

                'estado_pago_id' => $estadoPendiente->id,

                'amount' => $order->total_price,

                'reference' => $this->generarReferencia(),

            ]);

            return $payment->fresh([

                'order',

                'paymentMethod',

                'estadoPago',

            ]);

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Obtener pago
    |--------------------------------------------------------------------------
    */

    public function obtener(
        int $paymentId
    ): Payment {

        return Payment::with([

            'order',

            'paymentMethod',

            'estadoPago',

        ])

        ->findOrFail($paymentId);

    }

    /*
    |--------------------------------------------------------------------------
    | Obtener todos los pagos
    |--------------------------------------------------------------------------
    */

    public function obtenerTodos(): Collection
    {

        return Payment::with([

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
        int $orderId
    ): ?Payment {

        return Payment::with([

            'paymentMethod',

            'estadoPago',

        ])

        ->where('order_id', $orderId)

        ->first();

    }
        /*
    |--------------------------------------------------------------------------
    | Cambiar estado del pago
    |--------------------------------------------------------------------------
    */

    public function cambiarEstado(
        Payment $payment,
        string $codigoEstado
    ): Payment {

        $estado = $this->obtenerEstadoPago(
            $codigoEstado
        );

        $payment->actualizarEstado(
            $estado
        );

        return $payment->fresh([
            'estadoPago',
        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Confirmar pago
    |--------------------------------------------------------------------------
    */

    public function confirmarPago(
        Payment $payment,
        ?string $transactionId = null,
        ?array $transactionData = null
    ): Payment {

        return DB::transaction(function () use (
            $payment,
            $transactionId,
            $transactionData
        ) {

            if ($transactionId) {

                $payment->transaction_id = $transactionId;

            }

            if ($transactionData) {

                $payment->transaction_data = $transactionData;

            }

            $payment->save();

            return $this->cambiarEstado(
                $payment,
                'PAGADO'
            );

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Rechazar pago
    |--------------------------------------------------------------------------
    */

    public function rechazarPago(
        Payment $payment,
        ?array $transactionData = null
    ): Payment {

        if ($transactionData) {

            $payment->transaction_data = $transactionData;

            $payment->save();

        }

        return $this->cambiarEstado(
            $payment,
            'RECHAZADO'
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Cancelar pago
    |--------------------------------------------------------------------------
    */

    public function cancelarPago(
        Payment $payment
    ): Payment {

        if ($payment->isPagado()) {

            throw new RuntimeException(
                'No es posible cancelar un pago ya confirmado.'
            );

        }

        return $this->cambiarEstado(
            $payment,
            'CANCELADO'
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar pago
    |--------------------------------------------------------------------------
    */

    public function eliminarPago(
        Payment $payment
    ): bool {

        if (!$payment->isPendiente()) {

            throw new RuntimeException(
                'Solo se pueden eliminar pagos pendientes.'
            );

        }

        return $payment->delete();

    }
        /*
    |--------------------------------------------------------------------------
    | Validar pedido
    |--------------------------------------------------------------------------
    */

    private function validarPedido(
        Order $order
    ): void {

        if (!$order->exists) {

            throw new RuntimeException(
                'El pedido no existe.'
            );

        }

        if ($order->items()->doesntExist()) {

            throw new RuntimeException(
                'El pedido no contiene productos.'
            );

        }

        if ($order->payment()->exists()) {

            throw new RuntimeException(
                'El pedido ya tiene un pago registrado.'
            );

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Validar método de pago
    |--------------------------------------------------------------------------
    */

    private function validarMetodoPago(
        int $paymentMethodId
    ): PaymentMethod {

        return PaymentMethod::query()

            ->activos()

            ->findOrFail($paymentMethodId);

    }

    /*
    |--------------------------------------------------------------------------
    | Obtener estado del pago
    |--------------------------------------------------------------------------
    */

    private function obtenerEstadoPago(
        string $codigo
    ): EstadoPago {

        return EstadoPago::query()

            ->activos()

            ->whereRaw(
                'UPPER(codigo) = ?',
                [strtoupper($codigo)]
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

            $referencia = 'PAY-' .
                now()->format('YmdHis') .
                '-' .
                strtoupper(Str::random(6));

        } while (

            Payment::where(
                'reference',
                $referencia
            )->exists()

        );

        return $referencia;

    }
}