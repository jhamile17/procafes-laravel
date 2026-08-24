<?php

namespace App\Services\IA;

class IntentService
{
    public function detect(string $message): array
    {
        $message = $this->normalize($message);

        /*
        |--------------------------------------------------------------------------
        | 1. SALUDOS
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'hola',
            'holi',
            'hey',
            'buenas',
            'buenos dias',
            'buenas tardes',
            'buenas noches',
        ])) {
            return [
                'module' => 'greeting',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | 2. HORARIO
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'horario',
            'a que hora abren',
            'a que hora cierran',
            'cuando abren',
            'cuando cierran',
            'que hora atienden',
            'cuando atienden',
        ])) {
            return [
                'module' => 'business',
                'action' => 'hours',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | 3. UBICACIÓN
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'ubicacion',
            'direccion',
            'donde estan',
            'donde se encuentran',
            'como llegar',
            'local',
            'tienda',
            'sucursal',
        ])) {
            return [
                'module' => 'business',
                'action' => 'location',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | 4. DELIVERY
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'delivery',
            'envio',
            'reparto',
            'entrega a domicilio',
            'pedido a domicilio',
        ])) {
            return [
                'module' => 'business',
                'action' => 'delivery',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | 5. MÉTODOS DE PAGO
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'formas de pago',
            'forma de pago',
            'metodos de pago',
            'metodo de pago',
            'tarjeta',
            'visa',
            'mastercard',
            'credito',
            'debito',
            'yape',
            'plin',
            'efectivo',
        ])) {
            return [
                'module' => 'business',
                'action' => 'payments',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | 6. CONSTRUCCIÓN DE FILTROS
        |--------------------------------------------------------------------------
        */

        $filters = [];

        /*
        |--------------------------------------------------------------------------
        | 6.1 CATEGORÍAS ESPECÍFICAS
        |--------------------------------------------------------------------------
        */

        /*
        | Jugos
        */

        if ($this->contains($message, [
            'jugo',
            'jugos',
            'zumo',
        ])) {
            $filters['category'] = 'Jugos';
        }

        /*
        | Refrescos
        */

        if ($this->contains($message, [
            'refresco',
            'refrescos',
        ])) {
            $filters['category'] = 'Refrescos';
        }

        /*
        | Frappés
        */

        if ($this->contains($message, [
            'frappe',
            'frappes',
        ])) {
            $filters['category'] = 'Frappés';
        }

        /*
        | Cremoladas
        */

        if ($this->contains($message, [
            'cremolada',
            'cremoladas',
            'frozen',
        ])) {
            $filters['category'] = 'Cremoladas';
        }

        /*
        | Cold Brew
        */

        if ($this->contains($message, [
            'cold brew',
        ])) {
            $filters['category'] = 'Cold Brew';
        }

        /*
        | Piqueos
        */

        if ($this->contains($message, [
            'piqueo',
            'piqueos',
            'snack',
            'snacks',
            'yuca',
        ])) {
            $filters['category'] = 'Piqueos Artesanales';
        }

        /*
        | Sándwiches
        */

        if ($this->contains($message, [
            'sandwich',
            'sandwiches',
            'pan con',
        ])) {
            $filters['category'] = 'Sándwiches';
        }

        /*
        | Postres
        */

        if ($this->contains($message, [
            'postre',
            'postres',
        ])) {
            $filters['category'] = 'Postres';
        }

        /*
        |--------------------------------------------------------------------------
        | 6.2 CAFÉS FRÍOS
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'cafe frio',
            'cafes frios',
            'cafe helado',
            'cafes helados',
            'ice latte',
            'granizado de cafe',
            'shakerato',
            'affogato',
            'espresso en las rocas',
            'ice pichanaki',
        ])) {
            $filters['category'] = 'Cafés Fríos';
            $filters['tipo_consumo'] = 'Frío';
        }

        /*
        |--------------------------------------------------------------------------
        | 6.3 CAFÉS CALIENTES
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'cafe caliente',
            'cafes calientes',
            'cafe americano',
            'americano caliente',
            'espresso caliente',
            'capuccino caliente',
            'capuchino caliente',
        ])) {
            $filters['category'] = 'Cafés Calientes';
            $filters['tipo_consumo'] = 'Caliente';
        }

        /*
        |--------------------------------------------------------------------------
        | 6.4 BEBIDA FRÍA
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'bebida fria',
            'bebidas frias',
            'bebida fria',
            'bebidas frias',
            'algo frio',
            'algo fresco',
            'algo refrescante',
        ])) {
            $filters['category'] = 'Bebidas';
            $filters['tipo_consumo'] = 'Frío';
        }

        /*
        |--------------------------------------------------------------------------
        | 6.5 BEBIDA CALIENTE
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'bebida caliente',
            'bebidas calientes',
            'algo caliente',
        ])) {
            $filters['category'] = 'Bebidas';
            $filters['tipo_consumo'] = 'Caliente';
        }

        /*
        |--------------------------------------------------------------------------
        | 6.6 CAFÉ GENÉRICO
        |--------------------------------------------------------------------------
        */

        /*
        | Si simplemente dice "muéstrame cafés",
        | no asumimos que son calientes.
        */

        if (
            empty($filters['category']) &&
            $this->contains($message, [
                'cafe',
                'cafes',
            ])
        ) {
            $filters['category'] = 'Cafés';
        }

        /*
        |--------------------------------------------------------------------------
        | 6.7 BEBIDAS GENÉRICAS
        |--------------------------------------------------------------------------
        */

        if (
            empty($filters['category']) &&
            $this->contains($message, [
                'bebida',
                'bebidas',
            ])
        ) {
            $filters['category'] = 'Bebidas';
        }

        /*
        |--------------------------------------------------------------------------
        | 7. TIPO DE CONSUMO
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'caliente',
            'calientes',
            'calentito',
            'calentita',
        ])) {
            $filters['tipo_consumo'] = 'Caliente';
        }

        if ($this->contains($message, [
            'frio',
            'fria',
            'frios',
            'frias',
            'helado',
            'helada',
            'helados',
            'heladas',
        ])) {
            $filters['tipo_consumo'] = 'Frío';
        }

        /*
        | No usamos "ice" como tipo de consumo directamente,
        | porque puede formar parte del nombre de un producto.
        */

        /*
        |--------------------------------------------------------------------------
        | 8. PRECIO
        |--------------------------------------------------------------------------
        */

        /*
        |--------------------------------------------------------------------------
        | MENOS DE
        |--------------------------------------------------------------------------
        */

        if (preg_match(
            '/\b(?:menos de|menor a|por debajo de)\s*(?:s\/?\s*)?(\d+(?:[.,]\d+)?)/iu',
            $message,
            $matches
        )) {

            $filters['price_operator'] = '<';

            $filters['price_value'] = (float) str_replace(
                ',',
                '.',
                $matches[1]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | HASTA / MÁXIMO
        |--------------------------------------------------------------------------
        */

        elseif (preg_match(
            '/\b(?:hasta|maximo|maximo de|máximo|máximo de)\s*(?:s\/?\s*)?(\d+(?:[.,]\d+)?)/iu',
            $message,
            $matches
        )) {

            $filters['price_operator'] = '<=';

            $filters['price_value'] = (float) str_replace(
                ',',
                '.',
                $matches[1]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | MÁS DE
        |--------------------------------------------------------------------------
        */

        elseif (preg_match(
            '/\b(?:mas de|más de|mayor a|por encima de)\s*(?:s\/?\s*)?(\d+(?:[.,]\d+)?)/iu',
            $message,
            $matches
        )) {

            $filters['price_operator'] = '>';

            $filters['price_value'] = (float) str_replace(
                ',',
                '.',
                $matches[1]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DESDE
        |--------------------------------------------------------------------------
        */

        elseif (preg_match(
            '/\b(?:desde|a partir de)\s*(?:s\/?\s*)?(\d+(?:[.,]\d+)?)/iu',
            $message,
            $matches
        )) {

            $filters['price_operator'] = '>=';

            $filters['price_value'] = (float) str_replace(
                ',',
                '.',
                $matches[1]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PRECIO DIRECTO
        |--------------------------------------------------------------------------
        |
        | Ejemplo:
        | "café de S/ 8"
        | "producto de 10 soles"
        |
        */

        elseif (preg_match(
            '/\b(?:s\/?\s*)?(\d+(?:[.,]\d+)?)\s*(?:soles|sol)?\b/iu',
            $message,
            $matches
        )) {

            $filters['price_operator'] = '<=';

            $filters['price_value'] = (float) str_replace(
                ',',
                '.',
                $matches[1]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 9. PALABRAS CLAVE DE PRODUCTOS
        |--------------------------------------------------------------------------
        */

        $productKeywords = [

            'camu camu',
            'cold brew',

            'americano',
            'espresso',
            'latte',
            'moka',
            'capuccino',
            'capuchino',

            'granizado',
            'shakerato',
            'affogato',
            'submarino',

            'frappe',
            'frappes',

            'oreo',
            'vainilla',
            'chocolate',
            'cacao',
            'panela',
            'miel',
            'algarrobina',

            'fresa',
            'papaya',
            'piña',
            'platano',
            'maracuya',
            'guanabana',
            'naranja',
            'limon',
            'arandanos',

            'pollo',
            'chorizo',
            'cecina',
            'queso',
            'huevo',

            'yuca',
            'mixto',
        ];

        foreach ($productKeywords as $keyword) {

        /*
        |--------------------------------------------------------------------------
        | Evitar que el nombre de la categoría
        | se convierta también en keyword
        |--------------------------------------------------------------------------
        */

        $categoryKeywords = [
            'frappe',
            'frappes',
            'jugo',
            'jugos',
            'refresco',
            'refrescos',
            'piqueo',
            'piqueos',
            'sandwich',
            'sandwiches',
            'postre',
            'postres',
            'cold brew',
            'cremolada',
            'cremoladas',
        ];

        if (
            !empty($filters['category']) &&
            in_array($keyword, $categoryKeywords, true)
        ) {
            continue;
        }

        if (str_contains($message, $keyword)) {

            $filters['keyword'] = $keyword;

            break;
        }
    }

        /*
        |--------------------------------------------------------------------------
        | 10. PRODUCTO MÁS BARATO
        |--------------------------------------------------------------------------
        */

        if (
            empty($filters) &&
            $this->contains($message, [
                'producto mas barato',
                'producto mas economico',
                'cual es el mas barato',
                'cual es el mas economico',
            ])
        ) {
            return [
                'module' => 'product',
                'action' => 'cheapest',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | 11. PRODUCTO MÁS CARO
        |--------------------------------------------------------------------------
        */

        if (
            empty($filters) &&
            $this->contains($message, [
                'producto mas caro',
                'cual es el mas caro',
            ])
        ) {
            return [
                'module' => 'product',
                'action' => 'expensive',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | 12. MÁS VENDIDOS
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'mas vendido',
            'mas vendidos',
            'mas popular',
            'mas populares',
            'top productos',
            'productos favoritos',
        ])) {
            return [
                'module' => 'product',
                'action' => 'best_sellers',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | 13. COMPANION
        |--------------------------------------------------------------------------
        */
                if ($this->contains($message, [

            'con que puedo acompañar',
            'con que me recomiendas acompañar',
            'con que recomiendas acompañar',
            'con que lo acompaño',
            'con que puedo acompañarlo',
            'con que puedo acompañarla',
            'con que puedo acompañarlo',
            'con que puedo acompañarla',

            'que puedo acompañar',
            'que puedo comer con',
            'que puedo tomar con',

            'para acompañar',
            'algo para acompañar',

            'que combina con',
            'que combina',

            'acompañamiento',
            'acompanamiento',

        ])) {

            return [
                'module' => 'companion',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | 14. ALGO DULCE
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'algo dulce',
            'quiero algo dulce',
            'algo de dulce',
        ])) {

            return [
                'module' => 'recommendation',
                'filters' => array_merge($filters, [
                    'preference' => 'sweet',
                    'recommend' => true,
                ]),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | 15. ALGO SALADO
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'algo salado',
            'quiero algo salado',
        ])) {

            return [
                'module' => 'recommendation',
                'filters' => array_merge($filters, [
                    'preference' => 'salty',
                    'recommend' => true,
                ]),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | 16. DESAYUNO
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'desayuno',
            'desayunar',
            'para desayunar',
        ])) {

            return [
                'module' => 'recommendation',
                'filters' => array_merge($filters, [
                    'preference' => 'breakfast',
                    'recommend' => true,
                ]),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | 17. HACE CALOR
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'hace calor',
            'tengo calor',
            'algo refrescante',
            'algo fresco',
        ])) {

            return [
                'module' => 'recommendation',
                'filters' => array_merge($filters, [
                    'preference' => 'cold',
                    'recommend' => true,
                ]),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | 18. HACE FRÍO
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'hace frio',
            'tengo frio',
            'algo caliente para el frio',
        ])) {

            return [
                'module' => 'recommendation',
                'filters' => array_merge($filters, [
                    'preference' => 'hot',
                    'recommend' => true,
                ]),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | 19. HAMBRE
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'tengo hambre',
            'quiero comer',
            'algo para comer',
            'quiero algo para comer',
        ])) {

            return [
                'module' => 'recommendation',
                'filters' => array_merge($filters, [
                    'preference' => 'hungry',
                    'recommend' => true,
                ]),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | 20. RECOMENDACIONES
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'recomiendame',
            'recomienda',
            'recomiendas',
            'recomendar',
            'que recomiendas',
            'sugiere',
            'sugerencia',
            'algo rico',
            'algo bueno',
        ])) {

            return [
                'module' => 'recommendation',
                'filters' => array_merge($filters, [
                    'recommend' => true,
                ]),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | 21. PRODUCTOS DISPONIBLES
        |--------------------------------------------------------------------------
        */

        /*
        | IMPORTANTE:
        | "hay" ya NO se considera intención de disponibles.
        | "¿Qué hay de jugos?" debe buscar Jugos.
        */

        if (
            empty($filters) &&
            $this->contains($message, [
                'productos disponibles',
                'productos que tienen',
                'que productos tienen',
                'que tienen disponible',
                'que tienen disponibles',
                'que esta disponible',
                'que esta disponible',
            ])
        ) {

            return [
                'module' => 'product',
                'action' => 'available',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | 22. CARRITO
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'agregar al carrito',
            'añadir al carrito',
            'comprar',
            'lo quiero',
            'agregalo',
            'agregalo al carrito',
        ])) {

            return [
                'module' => 'cart',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | 23. BÚSQUEDA DE PRODUCTOS
        |--------------------------------------------------------------------------
        */

        if (!empty($filters)) {

            return [
                'module' => 'product',
                'filters' => $filters,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | 24. IA GENERAL
        |--------------------------------------------------------------------------
        */

        return [
            'module' => 'ai',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Normalizar texto
    |--------------------------------------------------------------------------
    */

    private function normalize(string $text): string
    {
        $text = mb_strtolower(trim($text));

        $text = str_replace(
            [
                'á',
                'é',
                'í',
                'ó',
                'ú',
                'ü',
                'ñ',
            ],
            [
                'a',
                'e',
                'i',
                'o',
                'u',
                'u',
                'ñ',
            ],
            $text
        );

        return preg_replace('/\s+/', ' ', $text);
    }

    /*
    |--------------------------------------------------------------------------
    | Contiene alguna palabra
    |--------------------------------------------------------------------------
    */

    private function contains(string $text, array $words): bool
    {
        foreach ($words as $word) {

            $word = $this->normalize($word);

            if (str_contains($text, $word)) {
                return true;
            }
        }

        return false;
    }
}