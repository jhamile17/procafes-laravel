<?php

namespace App\Services\IA;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductService
{
    /**
     * Cantidad máxima de productos que mostrará el chatbot.
     */
    private const MAX_PRODUCTS = 5;

    /**
     * Palabras utilizadas para recomendaciones.
     */
    private array $preferences = [

        'dulce' => [
            'oreo',
            'fresa',
            'chocolate',
            'cacao',
            'vainilla',
            'miel',
            'algarrobina',
            'panela',
            'frappé',
            'postre',
            'helado',
            'guanábana'
        ],

        'salado' => [
            'pollo',
            'queso',
            'jamón',
            'hamburguesa',
            'sándwich',
            'sandwich',
            'empanada',
            'chorizo',
            'cecina',
            'huevo',
            'yuca'
        ],

        'desayuno' => [
            'americano',
            'espresso',
            'latte',
            'capuccino',
            'capuchino',
            'pan',
            'sándwich',
            'sandwich'
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

        /*
        |--------------------------------------------------------------------------
        | Total real
        |--------------------------------------------------------------------------
        */

        $total = (clone $query)->count();

        /*
        |--------------------------------------------------------------------------
        | Máximo 5 productos
        |--------------------------------------------------------------------------
        */

        $products = $query
            ->limit(self::MAX_PRODUCTS)
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
        | Preferencia
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['preference'])) {

            switch ($filters['preference']) {

                /*
                |--------------------------------------------------------------------------
                | Dulce
                |--------------------------------------------------------------------------
                */

                case 'sweet':

                    $words = $this->preferences['dulce'];

                    $query->where(function ($q) use ($words) {

                        foreach ($words as $word) {

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
                |--------------------------------------------------------------------------
                | Salado
                |--------------------------------------------------------------------------
                */

                case 'salty':

                    $words = $this->preferences['salado'];

                    $query->where(function ($q) use ($words) {

                        foreach ($words as $word) {

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
                |--------------------------------------------------------------------------
                | Desayuno
                |--------------------------------------------------------------------------
                */

                case 'breakfast':

                    $words = $this->preferences['desayuno'];

                    $query->where(function ($q) use ($words) {

                        foreach ($words as $word) {

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
                |--------------------------------------------------------------------------
                | Hace calor
                |--------------------------------------------------------------------------
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
                |--------------------------------------------------------------------------
                | Hace frío
                |--------------------------------------------------------------------------
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
                |--------------------------------------------------------------------------
                | Tengo hambre
                |--------------------------------------------------------------------------
                */

                case 'hungry':

                    $query->whereHas(
                        'category',
                        function ($q) {

                            $q->whereIn(
                                'name',
                                [
                                    'Piqueos Artesanales',
                                    'Sándwiches'
                                ]
                            );

                        }
                    );

                    break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Aplicar filtros adicionales
        |--------------------------------------------------------------------------
        */

        $this->applyFilters(
            $query,
            $filters
        );

        /*
        |--------------------------------------------------------------------------
        | Total de recomendaciones
        |--------------------------------------------------------------------------
        */

        $total = (clone $query)->count();

        /*
        |--------------------------------------------------------------------------
        | Máximo 5
        |--------------------------------------------------------------------------
        */

        $products = $query
            ->limit(self::MAX_PRODUCTS)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Si no encuentra recomendaciones
        |--------------------------------------------------------------------------
        */

        if ($products->isEmpty()) {

            return [

                'message' =>
                    '😔 No encontré productos que coincidan con esa preferencia.',

                'products' => []

            ];
        }

        return [

            'message' => $this->recommendationMessage(
                $filters
            ),

            'products' => $this->formatProducts(
                $products
            )

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Productos para acompañar
    |--------------------------------------------------------------------------
    */

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
            ->inRandomOrder()
            ->limit(5)
            ->get();

        if ($products->isEmpty()) {

            return [
                'message' =>
                    '😔 En este momento no encontré opciones para acompañar tu bebida.',
                'products' => []
            ];
        }

        return [

            'message' =>
                '🥪 ¡Claro! Estas opciones combinan muy bien con tu bebida.',

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
            ->orderBy('sale_price', 'asc')
            ->first();

        if (!$product) {

            return [

                'message' =>
                    '😔 No encontré productos disponibles.',

                'products' => []

            ];
        }

        return [

            'message' =>
                "💰 El producto más económico es {$product->name}, con un precio de S/ " .
                number_format($product->sale_price, 2),

            'products' =>
                $this->formatProducts(
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
            ->orderBy('sale_price', 'desc')
            ->first();

        if (!$product) {

            return [

                'message' =>
                    '😔 No encontré productos disponibles.',

                'products' => []

            ];
        }

        return [

            'message' =>
                "💎 El producto de mayor precio es {$product->name}, con un precio de S/ " .
                number_format($product->sale_price, 2),

            'products' =>
                $this->formatProducts(
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
        $query = Product::query()
            ->with([
                'category',
                'tipoConsumo'
            ])
            ->disponibles()
            ->orderBy('name');

        $total = (clone $query)->count();

        $products = $query
            ->limit(self::MAX_PRODUCTS)
            ->get();

        if ($products->isEmpty()) {

            return [

                'message' =>
                    '😔 Actualmente no hay productos disponibles.',

                'products' => []

            ];
        }

        return [

            'message' =>
                $total > self::MAX_PRODUCTS
                    ? "✅ Tenemos {$total} productos disponibles. Te muestro algunos de ellos."
                    : "✅ Estos son nuestros productos disponibles.",

            'products' =>
                $this->formatProducts($products)

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
            ->withSum(
                'orderItems as total_sales',
                'quantity'
            )
            ->disponibles()
            ->orderByDesc('total_sales')
            ->limit(self::MAX_PRODUCTS)
            ->get();

        if ($products->isEmpty()) {

            return [

                'message' =>
                    '😔 Todavía no existen productos con ventas registradas.',

                'products' => []

            ];
        }

        return [

            'message' =>
                '🏆 Estos son algunos de nuestros productos más vendidos.',

            'products' =>
                $this->formatProducts($products)

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
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Categoría
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['category'])) {

            $category = mb_strtolower(
                trim($filters['category'])
            );

            $query->whereHas(
                'category',
                function ($q) use ($category) {

                    switch ($category) {

                        /*
                        | Todas las bebidas
                        */

                        case 'bebidas':

                            $q->whereIn(
                                'name',
                                [
                                    'Cafés Calientes',
                                    'Cafés Fríos',
                                    'Frappés',
                                    'Jugos',
                                    'Refrescos',
                                    'Cold Brew',
                                    'Cremoladas'
                                ]
                            );

                            break;

                        /*
                        | Jugos
                        */

                        case 'jugo':
                        case 'jugos':

                            $q->where(
                                'name',
                                'Jugos'
                            );

                            break;

                        /*
                        | Refrescos
                        */

                        case 'refresco':
                        case 'refrescos':

                            $q->where(
                                'name',
                                'Refrescos'
                            );

                            break;

                        /*
                        | Frappés
                        */

                        case 'frappé':
                        case 'frappés':
                        case 'frappe':
                        case 'frappes':

                            $q->where(
                                'name',
                                'Frappés'
                            );

                            break;

                        /*
                        | Cremoladas
                        */

                        case 'cremolada':
                        case 'cremoladas':
                        case 'frozen':

                            $q->where(
                                'name',
                                'Cremoladas'
                            );

                            break;

                        /*
                        | Cold Brew
                        */

                        case 'cold brew':

                            $q->where(
                                'name',
                                'Cold Brew'
                            );

                            break;

                        /*
                        | Cafés calientes
                        */

                        case 'café caliente':
                        case 'cafés calientes':
                        case 'cafe caliente':
                        case 'cafes calientes':

                            $q->where(
                                'name',
                                'Cafés Calientes'
                            );

                            break;

                        /*
                        | Cafés fríos
                        */

                        case 'café frío':
                        case 'cafés fríos':
                        case 'cafe frio':
                        case 'cafes frios':

                            $q->where(
                                'name',
                                'Cafés Fríos'
                            );

                            break;

                        /*
                        | Cafés
                        */

                        case 'café':
                        case 'cafés':
                        case 'cafe':
                        case 'cafes':

                            $q->whereIn(
                                'name',
                                [
                                    'Cafés Calientes',
                                    'Cafés Fríos',
                                    'Cold Brew'
                                ]
                            );

                            break;

                        /*
                        | Piqueos
                        */

                        case 'piqueo':
                        case 'piqueos':
                        case 'piqueos artesanales':

                            $q->where(
                                'name',
                                'Piqueos Artesanales'
                            );

                            break;

                        /*
                        | Sándwiches
                        */

                        case 'sándwich':
                        case 'sándwiches':
                        case 'sandwich':
                        case 'sandwiches':

                            $q->where(
                                'name',
                                'Sándwiches'
                            );

                            break;

                        /*
                        | Postres
                        */

                        case 'postre':
                        case 'postres':

                            $q->where(
                                'name',
                                'Postres'
                            );

                            break;

                        /*
                        | Categoría personalizada
                        */

                        default:

                            $q->where(
                                'name',
                                'LIKE',
                                "%{$category}%"
                            );

                            break;
                    }
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Tipo de consumo
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['tipo_consumo'])) {

            $tipo = trim(
                $filters['tipo_consumo']
            );

            $query->whereHas(
                'tipoConsumo',
                function ($q) use ($tipo) {

                    $q->where(
                        'nombre',
                        $tipo
                    );

                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Palabra clave
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['keyword'])) {

            $keyword = trim(
                $filters['keyword']
            );

            $query->where(
                function ($q) use ($keyword) {

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

                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PRECIO
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['price_value']) &&
            isset($filters['price_operator'])
        ) {

            $query->where(
                'sale_price',
                $filters['price_operator'],
                (float) $filters['price_value']
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
        | Orden aleatorio
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
    ): string {

        /*
        |--------------------------------------------------------------------------
        | Sin resultados
        |--------------------------------------------------------------------------
        */

        if ($products->isEmpty()) {

            if (!empty($filters['category'])) {

                $category = $filters['category'];

                return match ($category) {

                    'Jugos'
                        => '😔 No encontré jugos que coincidan con lo que buscas.',

                    'Refrescos'
                        => '😔 No encontré refrescos que coincidan con lo que buscas.',

                    'Frappés'
                        => '😔 No encontré frappés que coincidan con lo que buscas.',

                    'Cafés Calientes'
                        => '😔 No encontré cafés calientes con esas características.',

                    'Cafés Fríos'
                        => '😔 No encontré cafés fríos con esas características.',

                    'Piqueos Artesanales'
                        => '😔 No encontré piqueos con esas características.',

                    'Sándwiches'
                        => '😔 No encontré sándwiches con esas características.',

                    'Postres'
                        => '😔 No encontré postres con esas características.',

                    default
                        => '😔 No encontré productos que coincidan con lo que buscas.'
                };
            }

            return "😔 No encontré productos que coincidan con lo que buscas.";
        }


        /*
        |--------------------------------------------------------------------------
        | Valores utilizados
        |--------------------------------------------------------------------------
        */

        $category = $filters['category'] ?? null;

        $tipo = $filters['tipo_consumo'] ?? null;

        $keyword = $filters['keyword'] ?? null;

        $hasPrice = isset($filters['price_value'])
            && isset($filters['price_operator']);


        /*
        |--------------------------------------------------------------------------
        | Texto del precio
        |--------------------------------------------------------------------------
        */

        $priceText = '';

        if ($hasPrice) {

            $price = number_format(
                (float) $filters['price_value'],
                2
            );

            $priceText = match ($filters['price_operator']) {

                '<'
                    => "por menos de S/ {$price}",

                '<='
                    => "hasta S/ {$price}",

                '>'
                    => "por más de S/ {$price}",

                '>='
                    => "desde S/ {$price}",

                default
                    => "hasta S/ {$price}"
            };
        }


        /*
        |--------------------------------------------------------------------------
        | CATEGORÍA + TIPO DE CONSUMO
        |--------------------------------------------------------------------------
        */

        if ($category && $tipo) {

            /*
            |----------------------------------------------------------------------
            | Bebidas calientes
            |----------------------------------------------------------------------
            */

            if (
                mb_strtolower($category) === 'bebidas'
                && $tipo === 'Caliente'
            ) {

                if ($hasPrice) {

                    return "☕ Estas son algunas de nuestras bebidas calientes {$priceText}.";
                }

                return "☕ Estas son algunas de nuestras bebidas calientes. ¡Elige tu favorita!";
            }


            /*
            |----------------------------------------------------------------------
            | Bebidas frías
            |----------------------------------------------------------------------
            */

            if (
                mb_strtolower($category) === 'bebidas'
                && $tipo === 'Frío'
            ) {

                if ($hasPrice) {

                    return "🧊 Estas son algunas de nuestras bebidas frías {$priceText}.";
                }

                return "🧊 Estas son algunas de nuestras bebidas frías. ¡Elige tu favorita!";
            }


            /*
            |----------------------------------------------------------------------
            | Café caliente
            |----------------------------------------------------------------------
            */

            if (
                $category === 'Cafés Calientes'
                && $tipo === 'Caliente'
            ) {

                if ($hasPrice) {

                    return "☕ Estas son algunas de nuestras opciones de café caliente {$priceText}.";
                }

                return "☕ Estas son algunas de nuestras opciones de cafés calientes. ¡Elige tu favorito!";
            }


            /*
            |----------------------------------------------------------------------
            | Café frío
            |----------------------------------------------------------------------
            */

            if (
                $category === 'Cafés Fríos'
                && $tipo === 'Frío'
            ) {

                if ($hasPrice) {

                    return "🧊 Estas son algunas de nuestras opciones de café frío {$priceText}.";
                }

                return "🧊 Estas son algunas de nuestras opciones de cafés fríos. ¡Elige tu favorito!";
            }


            /*
            |----------------------------------------------------------------------
            | Otras categorías con tipo
            |----------------------------------------------------------------------
            */

            if ($hasPrice) {

                return "⭐ Estas son algunas opciones de {$category} {$priceText}.";
            }

            return "⭐ Estas son algunas opciones de {$category} para ti.";
        }


        /*
        |--------------------------------------------------------------------------
        | CATEGORÍA + PALABRA CLAVE
        |--------------------------------------------------------------------------
        */

        if ($category && $keyword) {

            if ($hasPrice) {

                return "🔎 Estas son algunas opciones de {$category} con {$keyword} {$priceText}.";
            }

            return "🔎 Estas son algunas opciones de {$category} con {$keyword}.";
        }


        /*
        |--------------------------------------------------------------------------
        | CATEGORÍA + PRECIO
        |--------------------------------------------------------------------------
        */

        if ($category && $hasPrice) {

            return match ($category) {

                'Jugos'
                    => "🥤 Estos son algunos de nuestros jugos {$priceText}.",

                'Refrescos'
                    => "🥤 Estos son algunos de nuestros refrescos {$priceText}.",

                'Frappés'
                    => "🥤 Estas son algunas opciones de frappés {$priceText}.",

                'Cafés Calientes'
                    => "☕ Estas son algunas opciones de cafés calientes {$priceText}.",

                'Cafés Fríos'
                    => "🧊 Estas son algunas opciones de cafés fríos {$priceText}.",

                'Cold Brew'
                    => "🧊 Estas son algunas opciones de Cold Brew {$priceText}.",

                'Cremoladas'
                    => "🍧 Estas son algunas opciones de cremoladas {$priceText}.",

                'Piqueos Artesanales'
                    => "🍽️ Estos son algunos de nuestros piqueos {$priceText}.",

                'Sándwiches'
                    => "🥪 Estas son algunas opciones de sándwiches {$priceText}.",

                'Postres'
                    => "🍰 Estas son algunas opciones de postres {$priceText}.",

                default
                    => "⭐ Estas son algunas opciones disponibles {$priceText}."
            };
        }


        /*
        |--------------------------------------------------------------------------
        | SOLO CATEGORÍA
        |--------------------------------------------------------------------------
        */

        if ($category) {

            return match ($category) {

                'Jugos'
                    => '🥤 Estos son algunos de nuestros jugos disponibles. ¡Elige tu favorito!',

                'Refrescos'
                    => '🥤 Estas son algunas opciones de nuestros refrescos.',

                'Frappés'
                    => '🥤 Mira estas opciones de frappés. ¡Seguro encontrarás uno que te guste!',

                'Cafés Calientes'
                    => '☕ Estas son algunas de nuestras opciones de cafés calientes. ¡Elige tu favorito!',

                'Cafés Fríos'
                    => '🧊 Estas son algunas de nuestras opciones de cafés fríos. ¡Elige tu favorito!',

                'Cold Brew'
                    => '🧊 Estas son algunas de nuestras opciones de Cold Brew.',

                'Cremoladas'
                    => '🍧 Mira estas deliciosas opciones de cremoladas.',

                'Bebidas'
                    => '🥤 Aquí tienes algunas de nuestras bebidas disponibles.',

                'Piqueos Artesanales'
                    => '🍽️ Mira algunas de nuestras opciones de piqueos artesanales.',

                'Sándwiches'
                    => '🥪 Estas son algunas opciones de nuestros sándwiches.',

                'Postres'
                    => '🍰 Mira algunas de nuestras opciones de postres.',

                default
                    => '⭐ Estas son algunas opciones disponibles.'
            };
        }


        /*
        |--------------------------------------------------------------------------
        | PALABRA CLAVE + PRECIO
        |--------------------------------------------------------------------------
        */

        if ($keyword && $hasPrice) {

            return "🔎 Encontré algunas opciones con {$keyword} {$priceText}.";
        }


        /*
        |--------------------------------------------------------------------------
        | SOLO PALABRA CLAVE
        |--------------------------------------------------------------------------
        */

        if ($keyword) {

            return "🔎 Estas son algunas opciones relacionadas con {$keyword}.";
        }


        /*
        |--------------------------------------------------------------------------
        | SOLO PRECIO
        |--------------------------------------------------------------------------
        */

        if ($hasPrice) {

            return "💰 Estas son algunas opciones {$priceText}.";
        }


        /*
        |--------------------------------------------------------------------------
        | BÚSQUEDA GENERAL
        |--------------------------------------------------------------------------
        */

        return "⭐ Encontré algunas opciones que podrían interesarte. ¡Échales un vistazo!";
    }

    /*
    |--------------------------------------------------------------------------
    | Mensajes de recomendaciones
    |--------------------------------------------------------------------------
    */

    private function recommendationMessage(
        array $filters
    ): string {

        return match (
            $filters['preference'] ?? null
        ) {

            'sweet'
                => "🍰 Si buscas algo dulce, estas son mis recomendaciones.",

            'salty'
                => "🧂 Si prefieres algo salado, estas opciones pueden gustarte.",

            'breakfast'
                => "🍳 Estas opciones son ideales para el desayuno.",

            'cold'
                => "🧊 Si quieres algo refrescante, estas bebidas frías pueden gustarte.",

            'hot'
                => "☕ Si quieres algo caliente, estas opciones pueden gustarte.",

            'hungry'
                => "🍔 Si tienes hambre, estas son algunas opciones que puedes disfrutar.",

            default
                => "⭐ Estas son mis recomendaciones."

        };
    }

    /*
    |--------------------------------------------------------------------------
    | Formatear productos
    |--------------------------------------------------------------------------
    */

    private function formatProducts(
        Collection $products
    ): array {

        return $products
            ->map(
                function (Product $product) {

                    return [

                        'id' =>
                            $product->id,

                        'name' =>
                            $product->name,

                        'description' =>
                            $product->description,

                        'price' =>
                            'S/ ' .
                            number_format(
                                $product->sale_price,
                                2
                            ),

                        'price_value' =>
                            (float) $product->sale_price,

                        'category' =>
                            $product->category?->name,

                        'tipo_consumo' =>
                            $product->tipoConsumo?->nombre,

                        'stock' =>
                            $product->stock,

                        'available' =>
                            $product->stock > 0,

                        'image' =>
                            $product->image_url,

                        'image_url' =>
                            $product->image_url,

                        'can_add_to_cart' =>
                            $product->can_add_to_cart,

                    ];
                }
            )
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