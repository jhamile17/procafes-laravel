<?php

namespace App\Services\Catalogo;

use App\Models\Product;
use Illuminate\Support\Collection;

class RecommendationService
{
    public function obtenerParaCarrito(
        Collection $items,
        int $limit = 4
    ): Collection {

        $productosEnCarrito = $items
            ->pluck('product_id')
            ->filter()
            ->values();

        return Product::query()
            ->where('status', true)
            ->where('stock', '>', 0)
            ->when(
                $productosEnCarrito->isNotEmpty(),
                fn ($query) => $query->whereNotIn('id', $productosEnCarrito)
            )
            ->latest()
            ->take($limit)
            ->get();
    }
}