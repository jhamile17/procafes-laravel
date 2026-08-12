<?php

namespace App\Services\IA;

class ResponseService
{
    public function products(string $title): string
    {
        return
            "😊 {$title}\n\n" .
            "Puedes revisar los productos que aparecen debajo de este mensaje.\n\n" .
            "💬 ¿Hay algo más en lo que pueda ayudarte?";
    }

    public function recommendation(string $title = null): string
    {
        return
            ($title ?? "⭐ Estas son algunas recomendaciones para ti.") .
            "\n\nEspero que alguna de ellas sea de tu agrado.\n\n" .
            "💬 Si deseas otra recomendación, solo dímelo.";
    }

    public function empty(): string
    {
        return
            "😔 No encontré productos que coincidan con tu búsqueda.\n\n" .
            "Puedes intentar buscar por:\n" .
            "• categoría\n" .
            "• ingrediente\n" .
            "• precio\n" .
            "• tipo de consumo\n\n" .
            "💬 Estoy aquí para ayudarte.";
    }

    public function location(): string
    {
        return
            "📍 Nuestro local se encuentra en:\n\n" .
            "Pichanaki, Junín - Perú.\n\n" .
            "☕ ¡Será un gusto atenderte!\n\n" .
            "💬 ¿Deseas conocer nuestro horario o ver nuestros productos?";
    }

    public function hours(): string
    {
        return
            "🕒 Nuestro horario de atención es:\n\n" .
            "📅 Lunes a Domingo\n" .
            "⏰ 8:00 a.m. - 10:00 p.m.\n\n" .
            "☕ ¡Te esperamos en PROCAFES!";
    }

    public function payments(): string
    {
        return
            "💳 Aceptamos los siguientes métodos de pago:\n\n" .
            "✅ Efectivo\n" .
            "✅ Yape\n" .
            "✅ Plin\n" .
            "✅ Tarjetas de crédito\n" .
            "✅ Tarjetas de débito\n\n" .
            "💬 ¿Deseas conocer algún producto?";
    }

    public function delivery(): string
    {
        return
            "🛵 Contamos con servicio de delivery para las zonas disponibles.\n\n" .
            "También puedes elegir la opción de recojo en tienda para algunos productos.\n\n" .
            "💬 ¿Te gustaría ver el menú?";
    }

    public function greeting(): string
    {
        return
            "¡Hola! 😊 Bienvenido a PROCAFES.\n\n" .
            "Puedo ayudarte a encontrar cafés, bebidas, postres, snacks o recomendarte productos según tus gustos.\n\n" .
            "☕ ¿Qué estás buscando hoy?";
    }

    public function fallback(): string
    {
        return
            "😊 No entendí completamente tu consulta.\n\n" .
            "Puedo ayudarte con:\n\n" .
            "☕ Cafés\n" .
            "🥤 Bebidas\n" .
            "🍔 Snacks\n" .
            "🍰 Postres\n" .
            "💰 Precios\n" .
            "📍 Ubicación\n" .
            "🕒 Horarios\n" .
            "🛵 Delivery\n" .
            "💳 Métodos de pago\n" .
            "⭐ Recomendaciones\n\n" .
            "¿Sobre qué te gustaría saber?";
    }
}