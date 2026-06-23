<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $message = mb_strtolower(trim($data['message']));

        if ($this->isBestSellerQuestion($message)) {
            return response()->json([
                'message' => 'Estos son algunos de los productos más vendidos de PROCAFES:',
                'products' => $this->bestSellers(),
            ]);
        }

        if ($this->isProductQuestion($message)) {
            $products = $this->searchProducts($message);

            return response()->json([
                'message' => $products->isNotEmpty()
                    ? 'Estos productos podrían interesarte:'
                    : 'No encontré productos disponibles con esa búsqueda. Puedes probar con “más vendidos” o escribir el nombre de un café.',
                'products' => $products,
            ]);
        }

        return response()->json([
            'message' => 'Puedo ayudarte con productos, café, pedidos, envíos, pagos, horarios y compras. Prueba escribiendo “muéstrame los más vendidos” o cuéntame qué tipo de café buscas.',
            'products' => [],
        ]);
    }

    private function isBestSellerQuestion(string $message): bool
    {
        return str_contains($message, 'más vendido')
            || str_contains($message, 'mas vendido')
            || str_contains($message, 'más populares')
            || str_contains($message, 'mas populares')
            || str_contains($message, 'favoritos');
    }

    private function isProductQuestion(string $message): bool
    {
        $keywords = [
            'café',
            'cafe',
            'producto',
            'productos',
            'recomienda',
            'recomiéndame',
            'recomendar',
            'mostrar',
            'muestra',
            'busco',
            'quiero',
        ];

        foreach ($keywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return true;
            }
        }

        return false;
    }

    private function bestSellers()
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

        /*
         * Mantiene el mismo orden de ventas que devolvió la consulta.
         */
        $ordered = $productIds
            ->map(fn ($id) => $products->firstWhere('id', $id))
            ->filter()
            ->values();

        /*
         * Si todavía no hay pedidos pagados/enviados,
         * muestra productos recientes disponibles.
         */
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

    private function searchProducts(string $message)
    {
        $search = trim(str_ireplace([
            'café',
            'cafe',
            'producto',
            'productos',
            'recomiéndame',
            'recomienda',
            'quiero',
            'busco',
            'muéstrame',
            'muestrame',
            'mostrar',
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