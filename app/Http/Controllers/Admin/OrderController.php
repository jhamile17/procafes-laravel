<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\EstadoPedido;
use Illuminate\Http\Request;
use App\Services\Ventas\OrderService;
use RuntimeException;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Listado de órdenes
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));

        $status = $request->get('status');

        /*
        |--------------------------------------------------------------------------
        | Estados activos
        |--------------------------------------------------------------------------
        |
        | Una sola consulta.
        |
        */

        $estados = EstadoPedido::query()
            ->where('status', true)
            ->orderBy('id')
            ->get([
                'id',
                'codigo',
                'nombre',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Órdenes
        |--------------------------------------------------------------------------
        */

        $orders = Order::query()

            ->with([
                'user',
                'estadoPedido',
                'payment.paymentMethod',
                'payment.estadoPago',
            ])

            ->when($q !== '', function ($query) use ($q) {

                $query->where(function ($query) use ($q) {

                    $query->where(
                        'numero_pedido',
                        'like',
                        "%{$q}%"
                    )

                    ->orWhereHas('user', function ($query) use ($q) {

                        $query
                            ->where('name', 'like', "%{$q}%")
                            ->orWhere(
                                'email',
                                'like',
                                "%{$q}%"
                            );

                    });

                });

            })

            ->when($status !== null && $status !== '', function ($query) use ($status) {

                $query->whereHas(
                    'estadoPedido',
                    function ($query) use ($status) {

                        $query->where(
                            'codigo',
                            $status
                        );

                    }
                );

            })

            ->latest()

            ->paginate(12)

            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Datos preparados para la vista
        |--------------------------------------------------------------------------
        */

        $orders->getCollection()->transform(
            function (Order $order) {

                $order->delivery_label = match (
                    strtoupper((string) $order->delivery_type)
                ) {

                    'RECOJO',
                    'PICKUP',
                    'RECOJO_EN_TIENDA'
                        => 'Recojo en tienda',

                    'DELIVERY',
                    'ENVIO',
                    'ENVÍO'
                        => 'Delivery',

                    default
                        => 'Delivery',

                };


                $order->created_at_formatted =
                    $order->created_at?->format(
                        'd/m/Y H:i'
                    );


                return $order;
            }
        );


        return view(
            'admin.orders.index',
            compact(
                'orders',
                'q',
                'status',
                'estados'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Detalle de orden
    |--------------------------------------------------------------------------
    */

    public function show(Order $order)
    {
        $order->load([
            'user',
            'estadoPedido',
            'items.product',
            'shippingAddress',
        ]);
         $order->delivery_label = match (
        strtoupper((string) $order->delivery_type)
            ) {
                'RECOJO',
                'PICKUP',
                'RECOJO_EN_TIENDA' => 'Recojo en tienda',

                'DELIVERY',
                'ENVIO',
                'ENVÍO' => 'Delivery',

                default => 'Delivery',
            };
            $order->created_at_formatted =
            $order->created_at?->format('d/m/Y H:i');

            $items = $order->items;

            $totals = [
                'items_subtotal' => $items->sum('subtotal'),
                'order_total' => $order->total_price,
            ];

            return view(
                'admin.orders.show',
                compact(
                    'order',
                    'items',
                    'totals'
                )
            );
        }

    /*
    |--------------------------------------------------------------------------
    | Actualizar estado
    |--------------------------------------------------------------------------
    */
   public function updateStatus(
    Request $request,
    Order $order
) {
    $request->validate([
        'estado_pedido_id' => [
            'required',
            'exists:estados_pedido,id',
        ],
    ]);

    $nuevoEstado = EstadoPedido::query()
        ->where('id', $request->estado_pedido_id)
        ->where('status', true)
        ->firstOrFail();

    $order->loadMissing([
        'estadoPedido',
        'items.product',
    ]);

    /*
    |--------------------------------------------------------------------------
    | El estado ya es el mismo
    |--------------------------------------------------------------------------
    */

    if ($order->estado_pedido_id === $nuevoEstado->id) {

        return back()->with(
            'info',
            'La orden ya tiene ese estado.'
        );
    }

    try {

        /*
        |--------------------------------------------------------------------------
        | Confirmar pedido
        |--------------------------------------------------------------------------
        */

        if ($nuevoEstado->esConfirmado()) {

            $this->orderService->confirmarPedido($order);

            return back()->with(
                'success',
                'Pedido confirmado correctamente.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Cancelar pedido
        |--------------------------------------------------------------------------
        */

        if ($nuevoEstado->esCancelado()) {

            $this->orderService->cancelarPedido($order);

            return back()->with(
                'success',
                'Pedido cancelado correctamente y stock restaurado.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Otros estados
        |--------------------------------------------------------------------------
        */

        $this->orderService->actualizarEstado(
            order: $order,
            codigoEstado: $nuevoEstado->codigo,
        );

        return back()->with(
            'success',
            'Estado actualizado correctamente y se notificó al cliente.'
        );

    } catch (RuntimeException $e) {

        return back()->with(
            'error',
            $e->getMessage()
        );
    }
}
}