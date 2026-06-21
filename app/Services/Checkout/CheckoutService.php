<?php

namespace App\Services\Checkout;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public const IGV_RATE = 0.18;

    /**
     * Construye líneas confiables desde la sesión.
     * La sesión solo aporta product_id y qty.
     */
    public function itemsFromSession(array $cart): Collection
    {
        $sessionItems = $cart['items'] ?? [];

        if (! is_array($sessionItems) || empty($sessionItems)) {
            throw ValidationException::withMessages([
                'cart' => 'Tu carrito está vacío.',
            ]);
        }

        $requestedItems = collect($sessionItems)
            ->map(fn (array $item) => [
                'product_id' => (int) ($item['product_id'] ?? 0),
                'qty' => (int) ($item['qty'] ?? 0),
            ])
            ->filter(fn (array $item) => $item['product_id'] > 0 && $item['qty'] > 0)
            ->values();

        if ($requestedItems->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => 'Tu carrito no contiene productos válidos.',
            ]);
        }

        $products = Product::query()
            ->whereIn('id', $requestedItems->pluck('product_id')->unique())
            ->where('status', true)
            ->get()
            ->keyBy('id');

        return $requestedItems->map(function (array $item) use ($products) {
            $product = $products->get($item['product_id']);

            if (! $product) {
                throw ValidationException::withMessages([
                    'cart' => 'Uno de los productos ya no está disponible.',
                ]);
            }

            if ($product->stock < $item['qty']) {
                throw ValidationException::withMessages([
                    'cart' => "Stock insuficiente para {$product->name}.",
                ]);
            }

            $quantity = $item['qty'];
            $unitPrice = round((float) $product->price, 2);
            $lineTotal = round($unitPrice * $quantity, 2);
            $lineBase = round($lineTotal / (1 + self::IGV_RATE), 2);

            return [
                'product_id' => $product->id,
                'name' => $product->name,
                'image' => $product->image,
                'image_url' => $product->image_url,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $lineTotal,
                'base' => $lineBase,
                'igv' => round($lineTotal - $lineBase, 2),
            ];
        })->values();
    }

    public function totals(Collection $items): array
    {
        $subtotal = round($items->sum('base'), 2);
        $igv = round($items->sum('igv'), 2);
        $total = round($items->sum('subtotal'), 2);

        return [
            'subtotal' => $subtotal,
            'igv' => $igv,
            'total' => $total,
            'igvRate' => self::IGV_RATE,
        ];
    }
}