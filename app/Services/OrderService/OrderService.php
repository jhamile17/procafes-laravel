<?php

namespace App\Services\OrderService;

use App\Models\Order;
use App\Models\Payment;
use App\Models\ShippingAddress;
use App\Models\User;
use App\Services\Checkout\CheckoutService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(
        private readonly CheckoutService $checkoutService,
    ) {
    }

    /**
     * Crea dirección, pedido, detalle y pago pendiente.
     *
     * @param array{
     *   address:string,
     *   city:string,
     *   state:string,
     *   zip_code:string,
     *   country:string,
     *   payment_method:string
     * } $data
     */
    public function createPendingOrder(User $user, array $cart, array $data): Order
    {
        $items = $this->checkoutService->itemsFromSession($cart);
        $totals = $this->checkoutService->totals($items);

        return DB::transaction(function () use ($user, $items, $totals, $data) {
            $shippingAddress = ShippingAddress::create([
                'user_id' => $user->id,
                'address' => $data['address'],
                'city' => $data['city'],
                'state' => $data['state'],
                'zip_code' => $data['zip_code'],
                'country' => $data['country'],
            ]);

            $order = Order::create([
                'user_id' => $user->id,
                'shipping_address_id' => $shippingAddress->id,
                'total_price' => $totals['total'],
                'status' => Order::STATUS_PENDING,
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            $order->payment()->create([
                'payment_method' => $data['payment_method'],
                'amount' => $totals['total'],
                'status' => Payment::STATUS_PENDING,
            ]);

            return $order->load(['items.product', 'payment', 'shippingAddress']);
        });
    }
}