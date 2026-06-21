<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\Checkout\CheckoutService;
use App\Services\OrderService\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CheckoutService $checkoutService,
        private readonly OrderService $orderService,
    ) {
    }

    public function index(Request $request): View|RedirectResponse
    {
        try {
            $items = $this->checkoutService->itemsFromSession(
                $request->session()->get('cart', [])
            );

            return view('checkout.index', [
                'items' => $items,
                ...$this->checkoutService->totals($items),
            ]);
        } catch (ValidationException $exception) {
            return redirect()
                ->route('products')
                ->withErrors($exception->errors());
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'address' => ['required', 'string', 'max:1000'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'zip_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'payment_method' => [
                'required',
                Rule::in(['mercadopago', 'bank_transfer', 'cash']),
            ],
        ]);

        $order = $this->orderService->createPendingOrder(
            $request->user(),
            $request->session()->get('cart', []),
            $data,
        );

        if ($order->payment->payment_method === 'mercadopago') {
            return redirect()
            ->route('mp.checkout', ['orderId' => $order->id]);
            
        }

        return redirect()
            ->route('customer.dashboard')
            ->with('success', "Pedido #{$order->id} creado correctamente.");
    }
}