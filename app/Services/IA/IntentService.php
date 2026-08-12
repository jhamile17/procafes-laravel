<?php

namespace App\Services\IA;

class IntentService
{
    public function detect(string $message): array
    {
        $message = mb_strtolower(trim($message));

        /*
        |--------------------------------------------------------------------------
        | Saludos
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'hola',
            'holi',
            'hey',
            'buenas',
            'buenos dias',
            'buenas tardes',
            'buenas noches'
        ])) {
            return [
                'module' => 'greeting'
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Horario
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'horario',
            'hora',
            'atienden',
            'abren',
            'cierran',
            'a que hora abren',
            'a qué hora abren',
            'a que hora cierran',
            'a qué hora cierran'
        ])) {
            return [
                'module' => 'business',
                'action' => 'hours'
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Ubicación
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'ubicacion',
            'ubicación',
            'direccion',
            'dirección',
            'donde',
            'dónde',
            'local',
            'tienda',
            'sucursal',
            'como llegar',
            'cómo llegar',
            'encuentran'
        ])) {
            return [
                'module' => 'business',
                'action' => 'location'
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Delivery
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'delivery',
            'envio',
            'envío',
            'reparto',
            'domicilio'
        ])) {
            return [
                'module' => 'business',
                'action' => 'delivery'
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Métodos de pago
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'pago',
            'pagos',
            'metodo de pago',
            'método de pago',
            'forma de pago',
            'formas de pago',
            'tarjeta',
            'tarjetas',
            'credito',
            'crédito',
            'debito',
            'débito',
            'visa',
            'mastercard',
            'yape',
            'plin',
            'efectivo'
        ])) {
            return [
                'module' => 'business',
                'action' => 'payments'
            ];
        }
        
        /*
        |--------------------------------------------------------------------------
        | Producto más barato
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [

            'más barato',
            'mas barato',
            'producto más barato',
            'producto mas barato',
            'económico',
            'economico',
            'más económico',
            'mas economico'

        ])) {

            return [

                'module' => 'product',
                'action' => 'cheapest'

            ];

        }

        /*
        |--------------------------------------------------------------------------
        | Producto más caro
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [

            'más caro',
            'mas caro',
            'producto más caro',
            'producto mas caro'

        ])) {

            return [

                'module' => 'product',
                'action' => 'expensive'

            ];

        }

        /*
        |--------------------------------------------------------------------------
        | Productos disponibles
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [

            'disponibles',
            'disponible',
            'hay',
            'hay disponibles',
            'con stock',
            'stock'

        ])) {

            return [

                'module' => 'product',
                'action' => 'available'

            ];

        }

        /*
        |--------------------------------------------------------------------------
        | Productos más vendidos
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [

            'más vendido',
            'mas vendido',
            'más vendidos',
            'mas vendidos',
            'más popular',
            'mas popular',
            'más populares',
            'mas populares',
            'top productos',
            'top de productos',
            'favoritos',
            'recomendados'

        ])) {

            return [

                'module' => 'product',
                'action' => 'best_sellers'

            ];

        }

        /*
        |--------------------------------------------------------------------------
        | Construcción de filtros
        |--------------------------------------------------------------------------
        */

        $filters = [];

        /*
        |--------------------------------------------------------------------------
        | Categorías
        |--------------------------------------------------------------------------
        */

        /*
        |--------------------------------------------------------------------------
        | Bebidas (todas)
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [
            'bebida',
            'bebidas'
        ])) {

            $filters['category'] = 'Bebidas';

        }

        /*
        |--------------------------------------------------------------------------
        | Cafés Calientes
        |--------------------------------------------------------------------------
        */

        if (
            $this->contains($message, [
                'café caliente',
                'cafés calientes',
                'cafe caliente',
                'cafes calientes'
            ])
            ||
            (
                $this->contains($message, [
                    'americano',
                    'espresso',
                    'capuccino',
                    'capuchino'
                ])
                &&
                !$this->contains($message, [
                    'ice',
                    'frío',
                    'frio',
                    'latte',
                    'granizado',
                    'shakerato',
                    'cold brew'
                ])
            )
        ) {

            $filters['category'] = 'Cafés Calientes';

        }

        /*
        |--------------------------------------------------------------------------
        | Cafés Fríos
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [

            'café frío',
            'café frio',
            'cafés fríos',
            'cafes frios',
            'ice latte',
            'granizado',
            'shakerato',
            'affogato',
            'submarino'

        ])) {

            $filters['category'] = 'Cafés Fríos';

        }

        /*
        |--------------------------------------------------------------------------
        | Cold Brew
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [

            'cold brew'

        ])) {

            $filters['category'] = 'Cold Brew';

        }

        /*
        |--------------------------------------------------------------------------
        | Jugos
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [

            'jugo',
            'jugos'

        ])) {

            $filters['category'] = 'Jugos';

        }

        /*
        |--------------------------------------------------------------------------
        | Refrescos
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [

            'refresco',
            'refrescos'

        ])) {

            $filters['category'] = 'Refrescos';

        }

        /*
        |--------------------------------------------------------------------------
        | Frappés
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [

            'frappé',
            'frappés',
            'frappe',
            'frappes'

        ])) {

            $filters['category'] = 'Frappés';

        }

        /*
        |--------------------------------------------------------------------------
        | Cremoladas
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [

            'cremolada',
            'cremoladas',
            'frozen'

        ])) {

            $filters['category'] = 'Cremoladas';

        }

        /*
        |--------------------------------------------------------------------------
        | Piqueos
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [

            'snack',
            'snacks',
            'piqueo',
            'piqueos',
            'yuca'

        ])) {

            $filters['category'] = 'Piqueos Artesanales';

        }

        /*
        |--------------------------------------------------------------------------
        | Sándwiches
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [

            'sandwich',
            'sándwich',
            'pan',
            'pollo',
            'chorizo',
            'queso',
            'huevo'

        ])) {

            $filters['category'] = 'Sándwiches';

        }

        /*
        |--------------------------------------------------------------------------
        | Postres
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [

            'postre',
            'postres'

        ])) {

            $filters['category'] = 'Postres';

        }

        /*
        |--------------------------------------------------------------------------
        | Tipo de consumo
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [

            'caliente',
            'calientes'

        ])) {

            $filters['tipo_consumo'] = 'Caliente';

        }

        if ($this->contains($message, [

            'frío',
            'frio',
            'fría',
            'fria',
            'fríos',
            'frios',
            'helado',
            'helada',
            'ice'

        ])) {

            $filters['tipo_consumo'] = 'Frío';

        }

        if ($this->contains($message, [

            'empacado',
            'molido',
            'grano',
            'bolsa'

        ])) {

            $filters['tipo_consumo'] = 'Empacado';

        }

        if ($this->contains($message, [

            'embotellado',
            'botella'

        ])) {

            $filters['tipo_consumo'] = 'Embotellado';

        }

        if ($this->contains($message, [

            'accesorio',
            'accesorios',
            'taza',
            'vaso'

        ])) {

            $filters['tipo_consumo'] = 'Accesorio';

        }

        /*
        |--------------------------------------------------------------------------
        | Precio máximo
        |--------------------------------------------------------------------------
        */

        if (preg_match(
            '/(?:menos de|hasta|maximo|máximo|menor a)\s*s?\/?\s*(\d+)/iu',
            $message,
            $matches
        )) {

            $filters['price_max'] = (float) $matches[1];

        }

        elseif (preg_match(
            '/s\/?\s*(\d+)/iu',
            $message,
            $matches
        )) {

            $filters['price_max'] = (float) $matches[1];

        }

        elseif ($this->contains($message, [

            'barato',
            'barata',
            'económico',
            'economico'

        ])) {

            $filters['price_max'] = 10;

        }

        /*
        |--------------------------------------------------------------------------
        | Palabras clave
        |--------------------------------------------------------------------------
        */

        $productKeywords = [

            // Cafés
            'americano',
            'espresso',
            'latte',
            'moka',
            'capuccino',
            'capuchino',
            'cold brew',
            'granizado',
            'shakerato',
            'affogato',
            'submarino',

            // Frappés
            'frappé',
            'frappe',

            // Sabores
            'oreo',
            'vainilla',
            'chocolate',
            'cacao',
            'panela',
            'miel',
            'algarrobina',

            // Frutas
            'fresa',
            'papaya',
            'piña',
            'platano',
            'plátano',
            'maracuyá',
            'maracuya',
            'guanábana',
            'guanabana',
            'naranja',
            'limón',
            'limon',
            'arándanos',
            'arandanos',
            'camu camu',

            // Sándwiches
            'pollo',
            'chorizo',
            'cecina',
            'queso',
            'huevo',

            // Piqueos
            'yuca',
            'mixto'

        ];

        foreach ($productKeywords as $keyword) {

            if (str_contains($message, $keyword)) {

                $filters['keyword'] = $keyword;
                break;

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Preferencias del usuario
        |--------------------------------------------------------------------------
        */

        /*
        |--------------------------------------------------------------------------
        | Algo dulce
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [

            'dulce',
            'postre',
            'postres',
            'quiero algo dulce'

        ])) {

            return [

                'module' => 'recommendation',

                'filters' => array_merge($filters, [

                    'preference' => 'sweet'

                ])

            ];

        }

        /*
        |--------------------------------------------------------------------------
        | Algo salado
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [

            'salado',
            'quiero algo salado'

        ])) {

            return [

                'module' => 'recommendation',

                'filters' => array_merge($filters, [

                    'preference' => 'salty'

                ])

            ];

        }

        /*
        |--------------------------------------------------------------------------
        | Desayuno
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [

            'desayuno',
            'desayunar',
            'para desayunar'

        ])) {

            return [

                'module' => 'recommendation',

                'filters' => array_merge($filters, [

                    'preference' => 'breakfast'

                ])

            ];

        }

        /*
        |--------------------------------------------------------------------------
        | Hace calor
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [

            'calor',
            'hace calor',
            'refrescante',
            'algo fresco'

        ])) {

            return [

                'module' => 'recommendation',

                'filters' => array_merge($filters, [

                    'preference' => 'cold'

                ])

            ];

        }

        /*
        |--------------------------------------------------------------------------
        | Hace frío
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [

            'hace frío',
            'hace frio',
            'tengo frío',
            'tengo frio'

        ])) {

            return [

                'module' => 'recommendation',

                'filters' => array_merge($filters, [

                    'preference' => 'hot'

                ])

            ];

        }

        /*
        |--------------------------------------------------------------------------
        | Tengo hambre
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [

            'hambre',
            'quiero comer',
            'comer',
            'almorzar',
            'cenar'

        ])) {

            return [

                'module' => 'recommendation',

                'filters' => array_merge($filters, [

                    'preference' => 'hungry'

                ])

            ];

        }

        /*
        |--------------------------------------------------------------------------
        | Acompañamiento
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [

            'acompañar',
            'acompañar',
            'acompañarlo',
            'acompañarla',
            'acompañe',
            'acompaño',
            'con que puedo acompañarlo',
            'con qué puedo acompañarlo',
            'con que puedo acompañarla',
            'con qué puedo acompañarla',
            'con que lo acompaño',
            'con qué lo acompaño',
            'para acompañar',
            'que combina',
            'qué combina',
            'que puedo comer con',
            'qué puedo comer con'

        ])) {

            return [

                'module' => 'companion'

            ];

        }

        /*
        |--------------------------------------------------------------------------
        | Solicitud de recomendaciones
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [

            'recomienda',
            'recomiéndame',
            'recomiendame',
            'recomendar',
            'recomiendas',
            'qué recomiendas',
            'que recomiendas',
            'sugerencia',
            'sugiere',
            'algo rico',
            'algo bueno'

        ])) {

            return [

                'module' => 'recommendation',

                'filters' => $filters

            ];

        }

        /*
        |--------------------------------------------------------------------------
        | Carrito
        |--------------------------------------------------------------------------
        */

        if ($this->contains($message, [

            'agregar',
            'agrégalo',
            'agregalo',
            'añadir',
            'añádelo',
            'comprar',
            'lo quiero',
            'llévame',
            'llevame'

        ])) {

            return [

                'module' => 'cart'

            ];

        }

        /*
        |--------------------------------------------------------------------------
        | Búsqueda de productos
        |--------------------------------------------------------------------------
        */

        if (!empty($filters)) {

            return [

                'module' => 'product',

                'filters' => $filters

            ];

        }

        /*
        |--------------------------------------------------------------------------
        | IA General
        |--------------------------------------------------------------------------
        */

        return [

            'module' => 'ai'

        ];
    }

    private function contains(string $text, array $words): bool
    {
        foreach ($words as $word) {

            if (str_contains($text, $word)) {
                return true;
            }

        }

        return false;
    }
}