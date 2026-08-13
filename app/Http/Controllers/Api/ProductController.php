<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AlertaStock;
use App\Models\NivelAlertaStock;
use App\Models\Product;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\DeviceToken;

class ProductController extends Controller
{
    /**
     * Obtener todos los productos.
     */
    public function index()
    {
        $products = Product::select(
            'id',
            'name',
            'stock',
            'stock_minimo',
            'sale_price',
            'image'
        )
        ->orderBy('name')
        ->get()
        ->map(function ($product) {
            return $this->formatProduct($product);
        });

        // IMPORTANTE:
        // Flutter espera directamente un array.
        return response()->json($products);
    }

    /**
     * Obtener productos con stock bajo o agotado.
     */
    public function alertasActuales()
    {
        $products = Product::where(function ($query) {
            $query->where('stock', '<=', 0)
                ->orWhereColumn('stock', '<=', 'stock_minimo');
        })
        ->select(
            'id',
            'name',
            'stock',
            'stock_minimo',
            'sale_price',
            'image'
        )
        ->orderBy('stock')
        ->get()
        ->map(function ($product) {
            return $this->formatProduct($product);
        });

        return response()->json($products);
    }

    /**
     * Obtener un producto por ID.
     */
    public function show(int $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Producto no encontrado',
            ], 404);
        }

        return response()->json(
            $this->formatProduct($product)
        );
    }

    /**
     * Actualizar stock.
     */
    public function updateStock(Request $request)
    {
        $validated = $request->validate([
            'id' => [
                'required',
                'integer',
                'exists:products,id',
            ],

            'stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'stock_minimo' => [
                'required',
                'integer',
                'min:0',
            ],
        ]);

        $product = Product::find($validated['id']);

        if (!$product) {
            return response()->json([
                'message' => 'Producto no encontrado',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR STOCK
        |--------------------------------------------------------------------------
        */

        $product->update([
            'stock' => $validated['stock'],
            'stock_minimo' => $validated['stock_minimo'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | CREAR ALERTA
        |--------------------------------------------------------------------------
        */

        if ($product->stock <= $product->stock_minimo) {

            /*
            | OUT = producto agotado
            | LOW = stock bajo
            */

            $codigoNivel = $product->stock <= 0
                ? 'OUT'
                : 'LOW';

            $nivel = NivelAlertaStock::where('codigo', $codigoNivel)
                ->where('status', true)
                ->first();

            if ($nivel) {

                $mensaje = $codigoNivel === 'OUT'
                    ? "Producto AGOTADO: {$product->name}"
                    : "Stock bajo ({$product->stock}): {$product->name}";

                $alerta = AlertaStock::create([
                    'product_id' => $product->id,
                    'nivel_alerta_id' => $nivel->id,
                    'stock_detectado' => $product->stock,
                    'mensaje' => $mensaje,
                    'enviado_correo' => false,
                    'enviado_app' => false,
                ]);

                /*
                |--------------------------------------------------------------------------
                | FIREBASE
                |--------------------------------------------------------------------------
                */

                $this->enviarNotificacionFirebase(
                    $product,
                    $mensaje,
                    $codigoNivel
                );
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Stock actualizado correctamente',
            'product' => $this->formatProduct($product),
        ]);
    }

    /**
     * Formatear producto para Flutter.
     */
    private function formatProduct(Product $product): array
    {
        return [
            'id' => $product->id,

            'name' => $product->name,

            /*
             * Flutter espera "price".
             * La BD actual utiliza "sale_price".
             */
            'price' => (float) $product->sale_price,

            'stock' => (int) $product->stock,

            'stock_minimo' => (int) $product->stock_minimo,

            'image_url' => $product->image
                ? asset('storage/' . $product->image)
                : null,
        ];
    }

    /**
     * Enviar notificación mediante Firebase.
     */
    private function enviarNotificacionFirebase(
        Product $product,
        string $mensaje,
        string $tipo
    ): void {
        try {

            $firebase = new FirebaseService();

            $accessToken = $firebase->getAccessToken();

            $projectId = config('services.firebase.project_id');

            if (!$projectId) {
                Log::warning(
                    'Firebase project_id no está configurado.'
                );

                return;
            }

            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            $tokens = DeviceToken::where('activo', true)
                ->pluck('token');

            foreach ($tokens as $token) {

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer '.$accessToken,
                    'Content-Type' => 'application/json',
                ])->post($url, [

                    'message' => [

                        'token' => $token,

                        'notification' => [
                            'title' => '🚨 Alerta de Inventario',
                            'body' => $mensaje,
                        ],

                        'data' => [
                            'screen' => 'alertas',
                            'producto_id' => (string)$product->id,
                            'tipo' => $tipo,
                        ],

                        'android' => [
                            'priority' => 'HIGH',
                            'notification' => [
                                'sound' => 'default',
                                'channel_id' => 'high_importance_channel',
                            ],
                        ],

                    ],

                ]);

                Log::info('Firebase', [
                    'token' => $token,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }

        } catch (\Throwable $e) {

            Log::error(
                'Error enviando notificación Firebase.',
                [
                    'error' => $e->getMessage(),
                ]
            );
        }
    }
}