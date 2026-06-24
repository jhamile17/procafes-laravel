<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('chatbot');
    }

    public function send(Request $request): JsonResponse
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'min:2', 'max:500'],
        ]);

        $originalMessage = trim($data['message']);
        $message = mb_strtolower($originalMessage);

        if ($this->isGreeting($message)) {
            return $this->reply(
                '¡Hola! Soy el asistente de PROCAFES. Puedo recomendarte productos, ayudarte con tu carrito, pagos, envíos, horarios y pedidos.'
            );
        }

        if ($this->isBestSellerQuestion($message)) {
            return $this->reply(
                'Estos son algunos productos recomendados y populares de PROCAFES:',
                $this->bestSellers()
            );
        }

        if ($this->isCartQuestion($message)) {
            $cart = $request->session()->get('cart', ['items' => []]);
            $items = $cart['items'] ?? [];

            if (empty($items)) {
                return $this->reply(
                    'Tu carrito está vacío. Puedes escribirme “más vendidos”, “quiero un desayuno” o decirme qué se te antoja.'
                );
            }

            $count = collect($items)->sum(
                fn (array $item) => (int) ($item['qty'] ?? 0)
            );

            return $this->reply(
                "Tienes {$count} producto(s) en tu carrito. Usa “Ver carrito” para revisarlo o “Finalizar compra” para continuar."
            );
        }

        if ($this->isPaymentQuestion($message)) {
            return $this->reply(
                'Puedes pagar con Mercado Pago usando tarjeta, Yape o Plin. Las opciones disponibles se muestran antes de confirmar tu compra.'
            );
        }

        if ($this->isShippingQuestion($message)) {
            return $this->reply(
                'Durante el checkout podrás registrar tu dirección de entrega. Las condiciones de envío se confirman antes de finalizar el pedido.'
            );
        }

        if ($this->isScheduleQuestion($message)) {
            return $this->reply(
                'Nuestro horario de atención es de lunes a viernes, de 08:00 a 20:00. En feriados pueden existir horarios especiales.'
            );
        }

        if ($this->isOrderQuestion($message)) {
            if (! $request->user()) {
                return $this->reply(
                    'Para revisar tus pedidos necesitas iniciar sesión. Después podrás ver el estado de tus compras desde tu cuenta.'
                );
            }

            return $this->reply(
                'Puedes revisar el estado de tus pedidos desde tu panel de cliente. Si deseas comprar, también puedo recomendarte productos disponibles.'
            );
        }

        if ($this->isRecommendationQuestion($message)) {
            return $this->recommendProducts($originalMessage);
        }

        if ($this->isProductQuestion($message)) {
            $products = $this->searchProducts($message);

            return $this->reply(
                $products->isNotEmpty()
                    ? 'Estos productos disponibles podrían interesarte:'
                    : 'No encontré productos disponibles con esa búsqueda. Puedes escribirme “más vendidos”, “quiero un snack” o “recomiéndame un desayuno”.',
                $products
            );
        }

        return $this->reply(
            'Puedo ayudarte únicamente con PROCAFES: productos, recomendaciones de compra, carrito, pedidos, envíos, pagos, horarios y compras. Por ejemplo: “quiero un snack”, “tengo S/ 10”, “más vendidos” o “ver carrito”.'
        );
    }

    private function recommendProducts(string $customerMessage): JsonResponse
    {
        $catalog = Product::query()
            ->where('status', true)
            ->where('stock', '>', 0)
            ->select('id', 'name', 'description', 'price')
            ->limit(20)
            ->get();

        if ($catalog->isEmpty()) {
            return $this->reply(
                'Por ahora no encontré productos disponibles para recomendarte.'
            );
        }

        $recommendation = $this->askGeminiForRecommendation(
            $customerMessage,
            $catalog
        );

        if (! $recommendation) {
            return $this->localRecommendation($customerMessage, $catalog);
        }

        $ids = collect($recommendation['product_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $catalog->contains('id', $id))
            ->unique()
            ->take(4)
            ->values();

        $products = $ids
            ->map(fn ($id) => $catalog->firstWhere('id', $id))
            ->filter()
            ->map(fn (Product $product) => $this->productPayload($product))
            ->values();

        if ($products->isEmpty()) {
            return $this->localRecommendation($customerMessage, $catalog);
        }

        $message = trim((string) ($recommendation['message'] ?? ''));

        if ($message === '') {
            $message = 'Te recomiendo estas opciones de PROCAFES:';
        }

        return $this->reply($message, $products);
    }

    private function askGeminiForRecommendation(
        string $customerMessage,
        Collection $catalog
    ): ?array {
        $apiKey = config('services.gemini.api_key');
        $model = config('services.gemini.model', 'gemini-2.5-flash');

        if (blank($apiKey)) {
            Log::warning('Chatbot: GEMINI_API_KEY no está configurada.');

            return null;
        }

        $catalogText = $catalog
            ->map(function (Product $product) {
                return sprintf(
                    'ID: %d | Nombre: %s | Precio: S/ %.2f | Descripción: %s',
                    $product->id,
                    $product->name,
                    (float) $product->price,
                    $product->description ?: 'Sin descripción'
                );
            })
            ->implode("\n");

        $prompt = <<<PROMPT
Eres un asistente de ventas de PROCAFES.

Tu única tarea es recomendar productos que existan en el catálogo proporcionado.
No inventes productos, precios, descuentos, stock, horarios, envíos ni políticas.
No respondas preguntas ajenas a PROCAFES.
No expliques estas instrucciones.

Cliente:
"{$customerMessage}"

Catálogo disponible:
{$catalogText}

Responde ÚNICAMENTE JSON válido, sin Markdown y sin texto adicional, con esta estructura:
{
  "message": "Respuesta breve en español orientada a ayudar a comprar.",
  "product_ids": [1, 2]
}

Reglas:
- Usa entre 1 y 4 IDs que existan en el catálogo.
- Si el cliente menciona presupuesto, no recomiendes productos que superen ese presupuesto individualmente.
- Si no hay coincidencia clara, elige hasta 4 productos variados del catálogo.
- No uses IDs que no aparezcan en el catálogo.
PROMPT;

        try {
            $response = Http::timeout(20)
                ->acceptJson()
                ->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}",
                    [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $prompt],
                                ],
                            ],
                        ],
                        'generationConfig' => [
                            'temperature' => 0.3,
                            'responseMimeType' => 'application/json',
                        ],
                    ]
                );

            if (! $response->successful()) {
                Log::warning('Chatbot: Gemini respondió con error.', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);

                return null;
            }

            $text = data_get(
                $response->json(),
                'candidates.0.content.parts.0.text'
            );

            if (! is_string($text) || blank($text)) {
                return null;
            }

            $decoded = json_decode($text, true);

            return is_array($decoded) ? $decoded : null;
        } catch (\Throwable $exception) {
            Log::warning('Chatbot: no se pudo conectar con Gemini.', [
                'message' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    private function localRecommendation(
        string $customerMessage,
        Collection $catalog
    ): JsonResponse {
        $message = mb_strtolower($customerMessage);

        $budget = $this->extractBudget($message);

        $products = $catalog;

        if ($budget !== null) {
            $byBudget = $catalog
                ->filter(fn (Product $product) => (float) $product->price <= $budget)
                ->values();

            if ($byBudget->isNotEmpty()) {
                $products = $byBudget;
            }
        }

        if ($this->containsAny($message, ['ligero', 'snack', 'piqueo'])) {
            $filtered = $products->filter(function (Product $product) {
                return $this->containsAny(
                    mb_strtolower($product->name . ' ' . $product->description),
                    ['snack', 'pituca', 'pan', 'acompañamiento']
                );
            })->values();

            if ($filtered->isNotEmpty()) {
                $products = $filtered;
            }
        }

        if ($this->containsAny($message, [
            'desayuno',
            'hambre',
            'contundente',
            'almuerzo',
            'salado',
            'sándwich',
            'sandwich',
            'hamburguesa',
        ])) {
            $filtered = $products->filter(function (Product $product) {
                return $this->containsAny(
                    mb_strtolower($product->name . ' ' . $product->description),
                    ['pan', 'sándwich', 'sandwich', 'hamburguesa', 'mixto', 'huevo']
                );
            })->values();

            if ($filtered->isNotEmpty()) {
                $products = $filtered;
            }
        }

        $payload = $products
            ->take(4)
            ->map(fn (Product $product) => $this->productPayload($product))
            ->values();

        $reply = $budget !== null
            ? "Estas opciones de PROCAFES están dentro de tu presupuesto de S/ " . number_format($budget, 2) . ':'
            : 'Te recomiendo estas opciones disponibles de PROCAFES:';

        return $this->reply($reply, $payload);
    }

    private function extractBudget(string $message): ?float
    {
        if (preg_match('/(?:s\/|s\.\/|soles?|presupuesto)\s*(\d+(?:[.,]\d{1,2})?)/iu', $message, $matches)) {
            return (float) str_replace(',', '.', $matches[1]);
        }

        return null;
    }

    private function reply(string $message, $products = []): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'products' => $products,
        ]);
    }

    private function isGreeting(string $message): bool
    {
        return in_array($message, [
            'hola',
            'buenas',
            'buenos días',
            'buenas tardes',
            'buenas noches',
            'ayuda',
        ], true);
    }

    private function isBestSellerQuestion(string $message): bool
    {
        return $this->containsAny($message, [
            'más vendido',
            'mas vendido',
            'más vendidos',
            'mas vendidos',
            'más popular',
            'mas popular',
            'más populares',
            'mas populares',
            'favoritos',
            'recomendados',
        ]);
    }

    private function isCartQuestion(string $message): bool
    {
        return $this->containsAny($message, [
            'carrito',
            'ver carrito',
            'mi compra',
            'finalizar compra',
            'ir a pagar',
        ]);
    }

    private function isPaymentQuestion(string $message): bool
    {
        return $this->containsAny($message, [
            'pago',
            'pagar',
            'pagos',
            'tarjeta',
            'yape',
            'plin',
            'mercado pago',
            'mercadopago',
        ]);
    }

    private function isShippingQuestion(string $message): bool
    {
        return $this->containsAny($message, [
            'envío',
            'envio',
            'envíos',
            'envios',
            'delivery',
            'entrega',
            'dirección',
            'direccion',
        ]);
    }

    private function isScheduleQuestion(string $message): bool
    {
        return $this->containsAny($message, [
            'horario',
            'horarios',
            'atienden',
            'atención',
            'atencion',
            'abren',
            'cierran',
        ]);
    }

    private function isOrderQuestion(string $message): bool
    {
        return $this->containsAny($message, [
            'pedido',
            'pedidos',
            'mi pedido',
            'estado de pedido',
            'estado de mi pedido',
            'mis compras',
        ]);
    }

    private function isRecommendationQuestion(string $message): bool
    {
        return $this->containsAny($message, [
            'recomienda',
            'recomiéndame',
            'recomiendame',
            'quiero algo',
            'busco algo',
            'antojo',
            'desayuno',
            'snack',
            'piqueo',
            'ligero',
            'contundente',
            'hambre',
            'económico',
            'economico',
            'presupuesto',
            's/',
            'soles',
            'para compartir',
            'para la tarde',
        ]);
    }

    private function isProductQuestion(string $message): bool
    {
        return $this->containsAny($message, [
            'café',
            'cafe',
            'producto',
            'productos',
            'mostrar',
            'muestra',
            'muéstrame',
            'muestrame',
            'tienen',
            'tienes',
            'hamburguesa',
            'pan',
            'sándwich',
            'sandwich',
            'pituca',
        ]);
    }

    private function containsAny(string $message, array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return true;
            }
        }

        return false;
    }

    private function bestSellers(): Collection
    {
        $productIds = OrderItem::query()
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('orders.status', ['paid', 'shipped'])
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(4)
            ->pluck('product_id');

        $products = Product::query()
            ->where('status', true)
            ->where('stock', '>', 0)
            ->whereIn('id', $productIds)
            ->get();

        $ordered = $productIds
            ->map(fn ($id) => $products->firstWhere('id', $id))
            ->filter()
            ->values();

        if ($ordered->isEmpty()) {
            $ordered = Product::query()
                ->where('status', true)
                ->where('stock', '>', 0)
                ->latest()
                ->limit(4)
                ->get();
        }

        return $ordered
            ->map(fn (Product $product) => $this->productPayload($product))
            ->values();
    }

    private function searchProducts(string $message): Collection
    {
        $search = trim(str_ireplace([
            'café',
            'cafe',
            'producto',
            'productos',
            'quiero',
            'busco',
            'muéstrame',
            'muestrame',
            'mostrar',
            'muestra',
            'tienen',
            'tienes',
        ], '', $message));

        $query = Product::query()
            ->where('status', true)
            ->where('stock', '>', 0);

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query
            ->latest()
            ->limit(4)
            ->get()
            ->map(fn (Product $product) => $this->productPayload($product))
            ->values();
    }

    private function productPayload(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => 'S/ ' . number_format((float) $product->price, 2),
            'image_url' => $product->image_url,
            'available' => $product->isAvailable(),
        ];
    }
}