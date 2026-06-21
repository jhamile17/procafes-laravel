<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class MercadoPagoController extends Controller
{
    /**
     * Muestra la página previa al pago de una orden pendiente.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $orderId = $request->integer('order_id');

        if (! $orderId) {
            return redirect()
                ->route('checkout')
                ->withErrors(['order' => 'Selecciona una orden para continuar con el pago.']);
        }

        $order = Order::query()
            ->with(['items.product', 'payment'])
            ->whereKey($orderId)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        if (! $order->isPending()) {
            return redirect()
                ->route('customer.dashboard')
                ->withErrors(['order' => 'Esta orden ya no está pendiente de pago.']);
        }

        if ($order->payment?->payment_method !== 'mercadopago') {
            return redirect()
                ->route('customer.dashboard')
                ->withErrors(['order' => 'Esta orden no usa Mercado Pago.']);
        }

        return view('payments.mercadopago', [
            'order' => $order,
        ]);
    }

    /**
     * Crea una preferencia desde los OrderItem guardados en la base de datos.
     */
    public function createPreference(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
        ]);

        $order = Order::query()
            ->with(['items.product', 'payment'])
            ->whereKey($data['order_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        if (! $order->isPending()) {
            return back()->withErrors([
                'order' => 'Esta orden ya no está pendiente de pago.',
            ]);
        }

        if ($order->payment?->payment_method !== 'mercadopago') {
            return back()->withErrors([
                'order' => 'Esta orden no fue creada para Mercado Pago.',
            ]);
        }

        if ($order->items->isEmpty()) {
            return back()->withErrors([
                'order' => 'La orden no tiene productos.',
            ]);
        }

        $accessToken = config('services.mercadopago.token');

        if (blank($accessToken)) {
            return back()->withErrors([
                'mercadopago' => 'Falta configurar MP_ACCESS_TOKEN en el archivo .env.',
            ]);
        }

        $items = $order->items->map(function ($item): array {
            return [
                'title' => $item->product?->name ?? "Producto #{$item->product_id}",
                'quantity' => $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'currency_id' => 'PEN',
            ];
        })->values()->all();

        $payload = [
            'items' => $items,
            'external_reference' => (string) $order->id,
            'back_urls' => [
                'success' => route('mp.success'),
                'failure' => route('mp.failure'),
                'pending' => route('mp.pending'),
            ],
            'auto_return' => 'approved',
            'notification_url' => route('mp.webhook'),
        ];

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post('https://api.mercadopago.com/checkout/preferences', $payload);

        if ($response->failed()) {
            report(new \RuntimeException(
                'Mercado Pago preference error: '.$response->body()
            ));

            return back()->withErrors([
                'mercadopago' => 'No se pudo iniciar el pago. Inténtalo nuevamente.',
            ]);
        }

        $preference = $response->json();
        $redirectUrl = $preference['init_point']
            ?? $preference['sandbox_init_point']
            ?? null;

        if (blank($redirectUrl)) {
            return back()->withErrors([
                'mercadopago' => 'Mercado Pago no devolvió una URL de pago válida.',
            ]);
        }

        return redirect()->away($redirectUrl);
    }

    /**
     * Esta URL solo muestra el resultado. El webhook es quien confirma el pago.
     */
    public function success(Request $request): View
    {
        return view('payments.status', [
            'status' => 'success',
            'data' => $request->all(),
        ]);
    }

    public function pending(Request $request): View
    {
        return view('payments.status', [
            'status' => 'pending',
            'data' => $request->all(),
        ]);
    }

    public function failure(Request $request): View
    {
        return view('payments.status', [
            'status' => 'failure',
            'data' => $request->all(),
        ]);
    }
}