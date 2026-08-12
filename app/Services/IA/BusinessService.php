<?php

namespace App\Services\IA;

class BusinessService
{
    public function answer(string $action): array
    {
        return match ($action) {

            /*
            |--------------------------------------------------------------------------
            | Horario
            |--------------------------------------------------------------------------
            */

            'hours' => [

                'message' =>
                    "🕒 Nuestro horario de atención es:\n\n" .
                    "📅 Lunes a Domingo\n" .
                    "⏰ 8:00 a.m. - 10:00 p.m.\n\n" .
                    "☕ ¡Te esperamos en PROCAFES!",

                'products' => []

            ],

            /*
            |--------------------------------------------------------------------------
            | Ubicación
            |--------------------------------------------------------------------------
            */

            'location' => [

                'message' =>
                    "📍 Nos encontramos en:\n\n" .
                    "Jr. 24 de Septiembre 841\n" .
                    "Pichanaqui, Junín - Perú.\n\n" .
                    "☕ ¡Será un gusto atenderte!",

                'products' => []

            ],

            /*
            |--------------------------------------------------------------------------
            | Métodos de pago
            |--------------------------------------------------------------------------
            */

            'payments' => [

                'message' =>
                    "💳 Aceptamos los siguientes métodos de pago:\n\n" .
                    "✅ Efectivo\n" .
                    "✅ Yape\n" .
                    "✅ Plin\n" .
                    "✅ Tarjetas de crédito\n" .
                    "✅ Tarjetas de débito",

                'products' => []

            ],

            /*
            |--------------------------------------------------------------------------
            | Delivery
            |--------------------------------------------------------------------------
            */

            'delivery' => [

                'message' =>
                    "🛵 Contamos con servicio de delivery dentro de Pichanaqui.\n\n" .
                    "También puedes elegir la opción de recojo en tienda para algunos productos.",

                'products' => []

            ],

            /*
            |--------------------------------------------------------------------------
            | WhatsApp
            |--------------------------------------------------------------------------
            */

            'whatsapp' => [

                'message' =>
                    "📱 Puedes escribirnos por WhatsApp al:\n\n" .
                    "📞 +51 955 236 237",

                'products' => []

            ],

            /*
            |--------------------------------------------------------------------------
            | Redes sociales
            |--------------------------------------------------------------------------
            */

            'social' => [

                'message' =>
                    "📲 Síguenos en nuestras redes sociales:\n\n" .
                    "📘 Facebook\n" .
                    "📸 Instagram\n" .
                    "🎵 TikTok",

                'products' => []

            ],

            /*
            |--------------------------------------------------------------------------
            | Recojo en tienda
            |--------------------------------------------------------------------------
            */

            'pickup' => [

                'message' =>
                    "🏪 Sí, puedes realizar tu pedido y recogerlo directamente en nuestra tienda.",

                'products' => []

            ],

            /*
            |--------------------------------------------------------------------------
            | Contacto
            |--------------------------------------------------------------------------
            */

            'contact' => [

                'message' =>
                    "☎️ Puedes comunicarte con nosotros mediante WhatsApp o visitarnos en nuestro local de Pichanaqui.",

                'products' => []

            ],

            /*
            |--------------------------------------------------------------------------
            | Default
            |--------------------------------------------------------------------------
            */

            default => [

                'message' =>
                    "Lo siento, no tengo información sobre esa consulta.",

                'products' => []

            ]
        };
    }
}