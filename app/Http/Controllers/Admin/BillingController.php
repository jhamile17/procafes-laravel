<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Pagos\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {
    }

    /**
     * Mostrar pedidos disponibles para facturación.
     */
    public function index(Request $request): View
    {
        $lookup = [
            'type'     => '',
            'document' => '',
            'name'     => '',
            'address'  => '',
            'raw'      => null,
        ];

        if (session()->has('lookup')) {
            $lookup = session('lookup');
        }

        /*
        |--------------------------------------------------------------------------
        | Pedidos
        |--------------------------------------------------------------------------
        |
        | Se muestran 8 pedidos por página.
        |
        */

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
            ->latest()
            ->paginate(8)
            ->withQueryString();

        return view(
            'admin.billing.index',
            compact(
                'lookup',
                'orders'
            )
        );
    }

    /**
     * Aprobar manualmente un pago realizado en tienda.
     *
     * La aprobación del pago NO genera directamente
     * una boleta o factura desde este controlador.
     *
     * El flujo de facturación se mantiene separado y
     * posteriormente puede utilizar NubeFact.
     */
    public function approvePayment(int $order): RedirectResponse
    {
        $order = Order::query()
            ->with([
                'payment.paymentMethod',
                'payment.estadoPago',
            ])
            ->findOrFail($order);

        /*
        |--------------------------------------------------------------------------
        | Verificar pago
        |--------------------------------------------------------------------------
        */

        if (! $order->payment) {
            return back()->with(
                'error',
                'El pedido no tiene un pago registrado.'
            );
        }

        $payment = $order->payment;

        /*
        |--------------------------------------------------------------------------
        | Verificar método de pago
        |--------------------------------------------------------------------------
        */

        if (! $this->paymentService->esPagoEnTienda($payment)) {
            return back()->with(
                'error',
                'Este pago no corresponde al método Pago en tienda.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Verificar estado
        |--------------------------------------------------------------------------
        */

        if (! $payment->isPendiente()) {
            return back()->with(
                'error',
                'El pago ya no se encuentra pendiente.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Confirmar pago
        |--------------------------------------------------------------------------
        */

        $this->paymentService->confirmarPago(
            payment: $payment
        );

        return back()->with(
            'success',
            'El pago del pedido '
            . $order->numero_pedido
            . ' fue aprobado correctamente.'
        );
    }

    /**
     * Consultar DNI o RUC mediante API Perú.
     */
    public function lookup(Request $request): RedirectResponse
    {
        $docType = $request->input(
            'doc_type',
            $request->input('type')
        );

        $docNumber = $request->input(
            'doc_number',
            $request->input('document')
        );

        $request->merge([
            'doc_type'   => $docType,
            'doc_number' => $docNumber,
        ]);

        $request->validate([
            'doc_type' => [
                'required',
                'in:dni,ruc,DNI,RUC',
            ],

            'doc_number' => [
                'required',
                'numeric',
            ],
        ]);

        $type = strtoupper($docType);

        $document = preg_replace(
            '/\D/',
            '',
            $docNumber
        );

        $lookup = [
            'type'     => $type,
            'document' => $document,
            'name'     => '',
            'address'  => '',
            'raw'      => null,
        ];

        /*
        |--------------------------------------------------------------------------
        | Validar DNI
        |--------------------------------------------------------------------------
        */

        if (
            $type === 'DNI' &&
            strlen($document) !== 8
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'El DNI debe tener 8 dígitos.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Validar RUC
        |--------------------------------------------------------------------------
        */

        if (
            $type === 'RUC' &&
            strlen($document) !== 11
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'El RUC debe tener 11 dígitos.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Configuración API Perú
        |--------------------------------------------------------------------------
        */

        $base = rtrim(
            env('DOCAPI_BASE', ''),
            '/'
        );

        $token = env(
            'DOCAPI_TOKEN',
            ''
        );

        if (! $base || ! $token) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Falta configurar DOCAPI_BASE o DOCAPI_TOKEN en el archivo .env.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Consulta API Perú
        |--------------------------------------------------------------------------
        */

        try {

            $response = Http::withToken($token)
                ->acceptJson()
                ->timeout(15)
                ->get(
                    $base
                    . '/'
                    . strtolower($type)
                    . '/'
                    . $document
                );

            if (! $response->successful()) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'No fue posible consultar el documento en API Perú.'
                    );
            }

            $data = $response->json();

            /*
            |--------------------------------------------------------------------------
            | Guardar resultado
            |--------------------------------------------------------------------------
            */

            $lookup['raw'] = $data;

            $lookup['name'] =
                data_get($data, 'data.nombre')
                ?? data_get($data, 'nombre')
                ?? data_get($data, 'razon_social')
                ?? '';

            $lookup['address'] =
                data_get($data, 'data.direccion')
                ?? data_get($data, 'direccion')
                ?? '';

            session()->flash(
                'lookup',
                $lookup
            );

            return back()->with(
                'success',
                'Documento consultado correctamente.'
            );

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Ocurrió un error al consultar API Perú.'
                );
        }
    }
}