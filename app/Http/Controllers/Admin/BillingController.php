<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ElectronicDocument;
use App\Models\EstadoComprobante;
use App\Services\Pagos\PaymentService;
use App\Services\Pagos\PaymentConfirmationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class BillingController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
        protected PaymentConfirmationService $paymentConfirmationService
    ) {
    }

    /**
     * Mostrar pedidos disponibles para facturación.
     */
    public function index(Request $request): View
    {
        $numeroPedido = trim(
            $request->get('numero_pedido', '')
        );
        $estado = $request->get('estado');
        $orders = Order::query()
            ->with([
                'user',
                'estadoPedido',
                'items.product',
                'payment.paymentMethod',
                'payment.estadoPago',
                'comprobante.estadoComprobante',
                'comprobante.electronicDocument',
            ])

            /*
            |--------------------------------------------------------------------------
            | Buscar por número de pedido
            |--------------------------------------------------------------------------
            */

            ->when(
                $numeroPedido !== '',
                function ($query) use ($numeroPedido) {

                    $query->where(
                        'numero_pedido',
                        'like',
                        '%' . $numeroPedido . '%'
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | Filtrar por estado del comprobante
            |--------------------------------------------------------------------------
            */

            ->when(
                $estado !== null && $estado !== '',
                function ($query) use ($estado) {

                    $query->whereHas(
                        'comprobante.estadoComprobante',
                        function ($query) use ($estado) {

                            $query->where(
                                'codigo',
                                $estado
                            );
                        }
                    );
                }
            )

            /*
            |--------------------------------------------------------------------------
            | Orden y paginación
            |--------------------------------------------------------------------------
            */

            ->latest()
            ->paginate(8)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Estados disponibles
        |--------------------------------------------------------------------------
        |
        | estados_comprobante NO tiene columna status.
        |
        */

        $estados = EstadoComprobante::query()
            ->orderBy('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Estadísticas existentes
        |--------------------------------------------------------------------------
        */

        $totalPedidos = Order::count();

        $totalEmitidos = ElectronicDocument::count();

        $totalPendientes = Order::doesntHave(
            'comprobante.electronicDocument'
        )->count();

        $totalAceptados = ElectronicDocument::where(
            'estado',
            'aceptado'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Vista
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.billing.index',
            compact(
                'orders',
                'numeroPedido',
                'estado',
                'estados',
                'totalPedidos',
                'totalEmitidos',
                'totalPendientes',
                'totalAceptados'
            )
        );
    }


    /**
     * Buscar pedido.
     */
    public function lookup(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'numero_pedido' => [
                    'required',
                    'string',
                    'max:30',
                ],
            ],
            [
                'numero_pedido.required' =>
                    'Ingresa el número de pedido.',

                'numero_pedido.max' =>
                    'El número de pedido es demasiado largo.',
            ]
        );

        return redirect()->route(
            'admin.billing.index',
            [
                'numero_pedido' => trim(
                    $request->numero_pedido
                ),
            ]
        );
    }


    /**
     * Aprobar manualmente un pago realizado en tienda.
     */
public function approvePayment(
        int $order
    ): RedirectResponse {

        try {
            $order = Order::query()
                ->with([
                    'estadoPedido',
                    'items.product',
                    'payment.paymentMethod',
                    'payment.estadoPago',
                ])
                ->findOrFail($order);

            if (! $order->payment) {
                return back()->with(
                    'error',
                    'El pedido no tiene un pago registrado.'
                );
            }
            $payment = $order->payment;
            if (! $this->paymentService->esPagoEnTienda($payment)) {

                return back()->with(
                    'error',
                    'Este pago no corresponde al método Pago en tienda.'
                );
            }
            if (! $payment->isPendiente()) {

                return back()->with(
                    'error',
                    'El pago ya no se encuentra pendiente.'
                );
            }
            $this->paymentConfirmationService->confirmar(
                payment: $payment
            );

            return back()->with(
                'success',
                'El pago del pedido '
                . $order->numero_pedido .
                ' fue aprobado correctamente y el stock fue actualizado.'
            );

        } catch (Throwable $e) {

            report($e);

            return back()->with(
                'error',
                'No se pudo aprobar el pago. '
                . $e->getMessage()
            );
        }
    }
}