<?php

namespace App\Services\Cart;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SessionCartService
{
    public const MAX_QTY_PER_ITEM = 8;
    public const MAX_QTY_TOTAL = 8;

    public function getCart(Request $request): array
    {
        return $request->session()->get('cart', [
            'items' => [],
        ]);
    }

    public function add(Request $request, int $productId, int $requestedQty = 1): array
    {
        $product = $this->findAvailableProduct($productId);

        $cart = $this->getCart($request);
        $items = $cart['items'] ?? [];

        $requestedQty = max(1, min(self::MAX_QTY_PER_ITEM, $requestedQty));
        $currentQty = (int) ($items[$productId]['qty'] ?? 0);
        $totalWithoutCurrent = $this->totalUnits($items) - $currentQty;

        $maxAllowed = min(
            self::MAX_QTY_PER_ITEM,
            self::MAX_QTY_TOTAL - $totalWithoutCurrent,
            $product->stock
        );

        if ($maxAllowed < 1) {
            throw ValidationException::withMessages([
                'cart' => 'No puedes agregar más productos al carrito.',
            ]);
        }

        $items[$productId] = [
            'product_id' => $product->id,
            'qty' => min($currentQty + $requestedQty, $maxAllowed),
        ];

        $request->session()->put('cart', ['items' => $items]);

        return $this->summary($request);
    }

    public function update(Request $request, int $productId, int $requestedQty): array
    {
        $cart = $this->getCart($request);
        $items = $cart['items'] ?? [];

        if (! isset($items[$productId])) {
            throw ValidationException::withMessages([
                'cart' => 'El producto no existe en tu carrito.',
            ]);
        }

        $product = $this->findAvailableProduct($productId);
        $requestedQty = max(1, min(self::MAX_QTY_PER_ITEM, $requestedQty));

        $totalWithoutCurrent = $this->totalUnits($items) - (int) $items[$productId]['qty'];

        $maxAllowed = min(
            self::MAX_QTY_PER_ITEM,
            self::MAX_QTY_TOTAL - $totalWithoutCurrent,
            $product->stock
        );

        if ($maxAllowed < 1) {
            throw ValidationException::withMessages([
                'cart' => 'No hay stock disponible para este producto.',
            ]);
        }

        $items[$productId]['qty'] = min($requestedQty, $maxAllowed);

        $request->session()->put('cart', ['items' => $items]);

        return $this->summary($request);
    }

    public function remove(Request $request, int $productId): array
    {
        $cart = $this->getCart($request);
        $items = $cart['items'] ?? [];

        unset($items[$productId]);

        $request->session()->put('cart', ['items' => $items]);

        return $this->summary($request);
    }

    public function clear(Request $request): array
    {
        $request->session()->forget('cart');

        return $this->emptySummary();
    }

    public function summary(Request $request): array
    {
        $cart = $this->getCart($request);
        $sessionItems = $cart['items'] ?? [];

        if (empty($sessionItems)) {
            return $this->emptySummary();
        }

        $productIds = collect($sessionItems)
            ->pluck('product_id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $products = Product::query()
            ->whereIn('id', $productIds)
            ->where('status', true)
            ->get()
            ->keyBy('id');

        $items = collect($sessionItems)
            ->map(function (array $item) use ($products) {
                $product = $products->get((int) $item['product_id']);

                if (! $product) {
                    return null;
                }

                $qty = min(
                    max(1, (int) $item['qty']),
                    self::MAX_QTY_PER_ITEM,
                    $product->stock
                );

                if ($qty < 1) {
                    return null;
                }

                $price = (float) $product->price;

                return [
                    'rowId' => (string) $product->id,
                    'id' => $product->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => round($price, 2),
                    'qty' => $qty,
                    'image' => $product->image,
                    'url' => $product->image_url,
                    'variant' => null,
                    'subtotal' => round($price * $qty, 2),
                ];
            })
            ->filter()
            ->values();

        $normalizedItems = $items
            ->mapWithKeys(fn (array $item) => [
                $item['product_id'] => [
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                ],
            ])
            ->all();

        $request->session()->put('cart', ['items' => $normalizedItems]);

        return [
            'items' => $items->keyBy('rowId')->all(),
            'count' => $items->sum('qty'),
            'total' => round($items->sum('subtotal'), 2),
        ];
    }

    private function findAvailableProduct(int $productId): Product
    {
        $product = Product::query()
            ->where('status', true)
            ->find($productId);

        if (! $product) {
            throw ValidationException::withMessages([
                'product_id' => 'El producto no está disponible.',
            ]);
        }

        if ($product->stock < 1) {
            throw ValidationException::withMessages([
                'product_id' => 'El producto no tiene stock disponible.',
            ]);
        }

        return $product;
    }

    private function totalUnits(array $items): int
    {
        return collect($items)->sum(
            fn (array $item) => (int) ($item['qty'] ?? 0)
        );
    }

    private function emptySummary(): array
    {
        return [
            'items' => [],
            'count' => 0,
            'total' => 0.0,
        ];
    }
}