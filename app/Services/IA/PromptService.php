<?php

namespace App\Services\IA;

use Illuminate\Support\Collection;

class PromptService
{
    public function build(Collection $products, string $question): array
    {
        $context = "";

        foreach ($products as $product) {

            $context .=
                "Producto: {$product->name}\n" .
                "Categoría: " . ($product->category?->name ?? 'Sin categoría') . "\n" .
                "Marca: " . ($product->brand?->name ?? 'Sin marca') . "\n" .
                "Tipo de consumo: " . ($product->tipoConsumo?->nombre ?? 'No especificado') . "\n" .
                "Precio: S/ " . number_format($product->sale_price, 2) . "\n" .
                "Stock: {$product->stock}\n" .
                "Descripción: " . ($product->description ?? 'Sin descripción') . "\n\n";

        }

        return [

            [
                'role' => 'system',

                'content' => <<<PROMPT
Eres el asistente virtual de PROCAFES.

Tu función es ayudar únicamente con información relacionada con PROCAFES.

Reglas:

- Responde siempre en español.
- Sé amable, breve y profesional.
- Nunca inventes productos.
- Nunca inventes precios.
- Nunca inventes promociones.
- Nunca inventes stock.
- Si un producto no existe, indícalo amablemente.
- Si el usuario pregunta algo fuera de PROCAFES, responde que solo puedes ayudar con información del negocio.
- Si existe información de productos, utilízala para responder.
- No menciones información que no aparezca en el contexto.

Productos disponibles:

{$context}
PROMPT
            ],

            [
                'role' => 'user',
                'content' => $question
            ]

        ];
    }
}