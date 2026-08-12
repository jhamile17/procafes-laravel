<?php

namespace App\Services\IA;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class ProductService
{
    /**
     * Preferencias utilizadas por el chatbot.
     */
    private array $preferences = [

        'dulce' => [
            'oreo',
            'fresa',
            'chocolate',
            'capuccino',
            'latte',
            'frappé',
            'postre'
        ],

        'salado' => [
            'pollo',
            'queso',
            'jamón',
            'hamburguesa',
            'sándwich',
            'empanada'
        ],

        'desayuno' => [
            'americano',
            'espresso',
            'latte',
            'capuccino',
            'pan'
        ],

        'calor' => [
            'Frío'
        ],

        'frio' => [
            'Caliente'
        ],

    ];

    /*
    |--------------------------------------------------------------------------
    | Buscar productos
    |--------------------------------------------------------------------------
    */

    public function search(array $filters): array
    {
        $query = Product::query()
            ->with([
                'category',
                'tipoConsumo'
            ])
            ->disponibles();

        $this->applyFilters($query, $filters);

        // Total de productos encontrados
        $total = (clone $query)->count();

        // Mostrar solo los primeros 5
        $products = $query
            ->limit(5)
            ->get();

        return [

            'message' => $this->buildMessage(
                $filters,
                $products,
                $total
            ),

            'products' => $this->formatProducts(
                $products
            )

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Recomendaciones
    |--------------------------------------------------------------------------
    */

    public function recommend(array $filters = []): array
    {
        $query = Product::query()
            ->with([
                'category',
                'tipoConsumo'
            ])
            ->disponibles();

        /*
        |--------------------------------------------------------------------------
        | Preferencias
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['preference'])) {

            switch ($filters['preference']) {

                /*
                |--------------------------------------------------------------
                | Algo dulce
                |--------------------------------------------------------------
                */

                case 'sweet':

                    $query->where(function ($q) {

                        foreach ($this->preferences['dulce'] as $word) {

                            $q->orWhere(
                                'name',
                                'LIKE',
                                "%{$word}%"
                            );

                            $q->orWhere(
                                'description',
                                'LIKE',
                                "%{$word}%"
                            );

                        }

                    });

                    break;

                /*
                |--------------------------------------------------------------
                | Algo salado
                |--------------------------------------------------------------
                */

                case 'salty':

                    $query->where(function ($q) {

                        foreach ($this->preferences['salado'] as $word) {

                            $q->orWhere(
                                'name',
                                'LIKE',
                                "%{$word}%"
                            );

                            $q->orWhere(
                                'description',
                                'LIKE',
                                "%{$word}%"
                            );

                        }

                    });

                    break;

                /*
                |--------------------------------------------------------------
                | Desayuno
                |--------------------------------------------------------------
                */

                case 'breakfast':

                    $query->where(function ($q) {

                        foreach ($this->preferences['desayuno'] as $word) {

                            $q->orWhere(
                                'name',
                                'LIKE',
                                "%{$word}%"
                            );

                        }

                    });

                    break;

                /*
                |--------------------------------------------------------------
                | Hace calor
                |--------------------------------------------------------------
                */

                case 'cold':

                    $query->whereHas(
                        'tipoConsumo',
                        function ($q) {

                            $q->where(
                                'nombre',
                                'Frío'
                            );

                        }
                    );

                    break;

                /*
                |--------------------------------------------------------------
                | Hace frío
                |--------------------------------------------------------------
                */

                case 'hot':

                    $query->whereHas(
                        'tipoConsumo',
                        function ($q) {

                            $q->where(
                                'nombre',
                                'Caliente'
                            );

                        }
                    );

                    break;

                /*
                |--------------------------------------------------------------
                | Tengo hambre
                |--------------------------------------------------------------
                */

                case 'hungry':

                    $query->whereHas(
                        'category',
                        function ($q) {

                            $q->whereIn(
                                'name',
                                [
                                    'Snacks',
                                    'Piqueos Artesanales'
                                ]
                            );

                        }
                    );

                    break;

            }

        }

        $this->applyFilters(
            $query,
            $filters
        );

        $products = $query
            ->limit(5)
            ->get();

        return [

            'message' => $this->recommendationMessage(
                $filters
            ),

            'products' => $this->formatProducts(
                $products
            )

        ];
    }

    public function companion(): array
    {
        $products = Product::query()
            ->with([
                'category',
                'tipoConsumo'
            ])
            ->disponibles()
            ->whereHas('category', function ($q) {

                $q->whereIn('name', [

                    'Piqueos Artesanales',
                    'Sándwiches'

                ]);

            })
            ->limit(5)
            ->get();

        return [

            'message' =>
                '🥪 Estas opciones combinan muy bien con tu bebida.',

            'products' =>
                $this->formatProducts($products)

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Producto más barato
    |--------------------------------------------------------------------------
    */

    public function cheapest(): array
    {
        $product = Product::query()
            ->with([
                'category',
                'tipoConsumo'
            ])
            ->disponibles()
            ->orderBy('sale_price')
            ->first();

        if (!$product) {

            return [

                'message' => 'No encontré productos disponibles.',

                'products' => []

            ];

        }

        return [

            'message' => "💰 El producto más económico es {$product->name}.",

            'products' => $this->formatProducts(
                collect([$product])
            )

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Producto más caro
    |--------------------------------------------------------------------------
    */

    public function expensive(): array
    {
        $product = Product::query()
            ->with([
                'category',
                'tipoConsumo'
            ])
            ->disponibles()
            ->orderByDesc('sale_price')
            ->first();

        if (!$product) {

            return [

                'message' => 'No encontré productos disponibles.',

                'products' => []

            ];

        }

        return [

            'message' => "💎 El producto de mayor precio es {$product->name}.",

            'products' => $this->formatProducts(
                collect([$product])
            )

        ];
    }
        /*
    |--------------------------------------------------------------------------
    | Productos disponibles
    |--------------------------------------------------------------------------
    */

    public function available(): array
    {
        $products = Product::query()
            ->with([
                'category',
                'tipoConsumo'
            ])
            ->disponibles()
            ->orderBy('name')
            ->get();

        return [

            'message' => '✅ Estos son los productos disponibles actualmente.',

            'products' => $this->formatProducts(
                $products
            )

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Productos más vendidos
    |--------------------------------------------------------------------------
    */

    public function bestSellers(): array
    {
        $products = Product::query()
            ->with([
                'category',
                'tipoConsumo'
            ])
            ->withSum('orderItems as total_sales', 'quantity')
            ->disponibles()
            ->orderByDesc('total_sales')
            ->limit(5)
            ->get();

        if ($products->isEmpty()) {

            return [

                'message' => 'Todavía no existen ventas registradas.',

                'products' => []

            ];

        }

        return [

            'message' => '🏆 Estos son nuestros productos más vendidos.',

            'products' => $this->formatProducts(
                $products
            )

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Aplicar filtros
    |--------------------------------------------------------------------------
    */

    private function applyFilters(
        Builder $query,
        array $filters
    ): void
    {

        /*
        |--------------------------------------------------------------------------
        | Categoría
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['category'])) {

        $category = mb_strtolower($filters['category']);

        $query->whereHas('category', function ($q) use ($category) {

            switch ($category) {

                case 'bebidas':

                    $q->whereIn('name', [
                        'Cafés Calientes',
                        'Cafés Fríos',
                        'Frappés',
                        'Jugos',
                        'Refrescos',
                        'Cold Brew',
                        'Cremoladas'
                    ]);
                    return;

                case 'jugos':

                    $q->where('name', 'Jugos');
                    return;

                case 'frappés':
                case 'frappes':

                    $q->where('name', 'Frappés');
                    return;

                case 'refrescos':

                    $q->where('name', 'Refrescos');
                    return;

                case 'cremoladas':

                    $q->where('name', 'Cremoladas');
                    return;

                case 'cold brew':

                    $q->where('name', 'Cold Brew');
                    return;

                case 'cafés fríos':
                case 'cafes frios':

                    $q->where('name', 'Cafés Fríos');
                    return;

                case 'cafés':
                case 'cafes':

                    $q->whereIn('name', [
                        'Cafés Calientes',
                        'Cafés Fríos',
                        'Cold Brew'
                    ]);
                    return;

                case 'piqueos':

                    $q->whereIn('name', [
                        'Piqueos Artesanales',
                        'Sándwiches'
                    ]);
                    return;

                default:

                    $q->where('name', 'LIKE', "%{$category}%");

            }

        });

    }

        /*
        |--------------------------------------------------------------------------
        | Tipo de consumo (antes tipo consumo)
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['tipo_consumo'])) {

            $query->whereHas('tipoConsumo', function ($q) use ($filters) {

                $q->where(
                    'nombre',
                    $filters['tipo_consumo']
                );

            });

        }

        /*
        |--------------------------------------------------------------------------
        | Palabra clave
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['keyword'])) {

            $keyword = trim($filters['keyword']);

            $query->where(function ($q) use ($keyword) {

                $q->where(
                    'name',
                    'LIKE',
                    "%{$keyword}%"
                )

                ->orWhere(
                    'description',
                    'LIKE',
                    "%{$keyword}%"
                );

            });

        }

        /*
        |--------------------------------------------------------------------------
        | Precio máximo
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['price_max'])) {

            $query->where(
                'sale_price',
                '<=',
                $filters['price_max']
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Disponibilidad
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['available'])) {

            $query->where(
                'stock',
                '>',
                0
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Recomendación aleatoria
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['recommend'])) {

            $query->inRandomOrder();

        }

    }
    
    /*
    |--------------------------------------------------------------------------
    | Mensaje de búsqueda
    |--------------------------------------------------------------------------
    */

    private function buildMessage(
        array $filters,
        Collection $products,
        int $total
    ): string
    {
        if ($products->isEmpty()) {
            return "😔 No encontré productos con esas características.";
        }

        if (!empty($filters['recommend'])) {
            return "⭐ Te recomiendo estos productos.";
        }

        $shown = $products->count();

        /*
        |--------------------------------------------------------------------------
        | Categoría + Tipo de consumo
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['category']) && !empty($filters['tipo_consumo'])) {

            if ($total > $shown) {
                return "Encontré {$total} productos de {$filters['category']} ({$filters['tipo_consumo']}). Te muestro los primeros {$shown}.";
            }

            return "Encontré {$total} productos de {$filters['category']} ({$filters['tipo_consumo']}).";
        }

        /*
        |--------------------------------------------------------------------------
        | Solo categoría
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['category'])) {

            if ($total > $shown) {
                return "Encontré {$total} productos de {$filters['category']}. Te muestro los primeros {$shown}.";
            }

            return "Encontré {$total} productos de {$filters['category']}.";
        }

        /*
        |--------------------------------------------------------------------------
        | Búsqueda general
        |--------------------------------------------------------------------------
        */

        if ($total > $shown) {
            return "Encontré {$total} productos. Te muestro los primeros {$shown}.";
        }

        return "Encontré {$total} productos para ti.";
    }

    /*
    |--------------------------------------------------------------------------
    | Mensajes de recomendaciones
    |--------------------------------------------------------------------------
    */

    private function recommendationMessage(
        array $filters
    ): string
    {
        if (!empty($filters['preference'])) {

            return match ($filters['preference']) {

                'sweet'
                    => "🍰 Si buscas algo dulce, estas son mis recomendaciones.",

                'salty'
                    => "🧂 Si prefieres algo salado, estas opciones pueden gustarte.",

                'breakfast'
                    => "🍳 Estas opciones son ideales para el desayuno.",

                'cold'
                    => "🥤 Estas bebidas frías son perfectas para refrescarte.",

                'hot'
                    => "☕ Estas bebidas calientes son ideales para disfrutar un buen café.",

                'hungry'
                    => "🍔 Si tienes hambre, estas son mis mejores recomendaciones.",

                default
                    => "⭐ Estas son mis recomendaciones."
            };
        }

        return "⭐ Estas son mis recomendaciones.";
    }

    /*
    |--------------------------------------------------------------------------
    | Formatear productos
    |--------------------------------------------------------------------------
    */

    private function formatProducts(
        Collection $products
    ): array {

        return $products->map(function (Product $product) {

            return [

                'id' => $product->id,

                'name' => $product->name,

                'description' => $product->description,

                'price' => 'S/ ' . number_format(
                    $product->sale_price,
                    2
                ),

                'price_value' => (float) $product->sale_price,

                'category' => $product->category?->name,

                'tipo_consumo' => $product->tipoConsumo?->nombre,

                'stock' => $product->stock,

                'available' => $product->stock > 0,

                'image' => $product->image_url,

                'image_url' => $product->image_url,

                'can_add_to_cart' => $product->can_add_to_cart,

            ];

        })
        ->values()
        ->toArray();

    }

    /*
    |--------------------------------------------------------------------------
    | Pie de respuesta
    |--------------------------------------------------------------------------
    */

    private function footer(): string
    {
        return "\n\n💬 ¿Hay algo más en lo que pueda ayudarte?";
    }

}