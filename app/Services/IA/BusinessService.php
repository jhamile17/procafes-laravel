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
                    "⏰ 8:00 a.m. - 11:00 p.m.\n\n" .
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
                    "📍 Puedes visitarnos en:\n\n" .
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
                "💳 En PROCAFES contamos con dos métodos de pago:\n\n" .
                "💵 Efectivo\n" .
                "🛒 Mercado Pago\n\n" .
                "☕ Puedes elegir el método que prefieras al realizar tu pedido.",

            'products' => []

        ],

            /*
            |--------------------------------------------------------------------------
            | Delivery
            |--------------------------------------------------------------------------
            */

            'delivery' => [
                'message' =>
                    "🛵 Sí, contamos con servicio de delivery dentro de Pichanaqui.\n\n" .
                    "También puedes elegir la opción de recojo en tienda para tus pedidos.\n\n" .
                    "📦 La disponibilidad puede depender de la zona y del pedido.",

                'products' => []
            ],

            /*
            |--------------------------------------------------------------------------
            | WhatsApp
            |--------------------------------------------------------------------------
            */

            'whatsapp' => [
                'message' =>
                    "📱 Puedes comunicarte directamente con PROCAFES por WhatsApp:\n\n" .
                    "📞 +51 955 236 237\n\n" .
                    "☕ Estaremos encantados de atenderte.",

                'products' => []
            ],

            /*
            |--------------------------------------------------------------------------
            | Recojo en tienda
            |--------------------------------------------------------------------------
            */

            'pickup' => [
                'message' =>
                    "🏪 Sí, puedes realizar tu pedido y elegir el recojo en tienda.\n\n" .
                    "📦 Una vez preparado tu pedido, podrás recogerlo directamente en nuestro local.",

                'products' => []
            ],

            /*
            |--------------------------------------------------------------------------
            | Contacto
            |--------------------------------------------------------------------------
            */

            'contact' => [
                'message' =>
                    "☎️ Puedes comunicarte con PROCAFES a través de nuestro WhatsApp:\n\n" .
                    "📞 +51 955 236 237\n\n" .
                    "📍 También puedes visitarnos en:\n" .
                    "Jr. 24 de Septiembre 841, Pichanaqui, Junín - Perú.",

                'products' => []
            ],

            /*
            |--------------------------------------------------------------------------
            | Default
            |--------------------------------------------------------------------------
            */

            default => [
                'message' =>
                    "😔 No encontré información sobre esa consulta.\n\n" .
                    "Puedo ayudarte con:\n\n" .
                    "🕒 Horarios\n" .
                    "📍 Ubicación\n" .
                    "🛵 Delivery\n" .
                    "💳 Métodos de pago\n" .
                    "📱 WhatsApp\n" .
                    "🏪 Recojo en tienda\n" .
                    "☕ Productos",

                'products' => []
            ]
        };
    }
}