<?php

namespace App\Services;

use App\Models\AlertaStock;
use App\Models\DeviceToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AlertasStockService
{
    /**
     * Revisar stock de un producto y generar alerta + notificación
     */
    public function revisarStock($product)
    {
        if ($product->stock <= $product->stock_minimo) {

            $tipo = $product->stock == 0 ? 'agotado' : 'bajo';

            $mensaje = $tipo === 'agotado'
                ? "Producto AGOTADO: {$product->name}"
                : "Stock bajo ({$product->stock}): {$product->name}";

            // Evitar enviar muchas alertas (30 minutos)
            $ultimaAlerta = AlertaStock::where('product_id', $product->id)
                ->latest('fecha_alerta')
                ->first();

            $crearAlerta = false;

            if (!$ultimaAlerta) {
                $crearAlerta = true;
            } else {

                $minutos = Carbon::parse($ultimaAlerta->fecha_alerta)
                    ->diffInMinutes(now());

                if ($minutos >= 30) {
                    $crearAlerta = true;
                }
            }

            if (!$crearAlerta) {
                return;
            }

            // Guardar alerta
            AlertaStock::create([
                'product_id'       => $product->id,
                'stock_detectado'  => $product->stock,
                'mensaje'          => $mensaje,
                'fecha_alerta'     => now(),
            ]);

            Log::info("🆕 Alerta registrada: {$mensaje}");

            // Obtener tokens
            $tokens = DeviceToken::where('activo', true)
                ->pluck('token');

            Log::info("📱 Tokens encontrados: " . $tokens->count());

            foreach ($tokens as $token) {

                try {

                    $this->enviarNotificacion(
                        $token,
                        $mensaje,
                        $tipo,
                        $product->id,
                        $product->name,
                        $product->stock,
                        $product->image
                    );

                } catch (\Exception $e) {

                    Log::error("❌ Error enviando notificación: " . $e->getMessage());

                }

            }
        }
    }

    /**
     * Enviar notificación mediante Firebase Cloud Messaging
     */
    private function enviarNotificacion(
        $token,
        $mensaje,
        $tipo,
        $productoId,
        $productoName,
        $stock,
        $imagen = null
    ) {

        $firebase = new FirebaseService();

        $accessToken = $firebase->getAccessToken();

        $url = "https://fcm.googleapis.com/v1/projects/my-project-de-entrega/messages:send";

        $payload = [

            "message" => [

                "token" => $token,

                "notification" => [
                    "title" => "🚨 Alerta de Inventario",
                    "body"  => $mensaje,
                ],

                "data" => [
                    "tipo"         => "producto",
                    "nivel"        => $tipo,
                    "producto_id"  => (string) $productoId,
                    "producto"     => $productoName,
                    "stock"        => (string) $stock,
                    "title"        => "🚨 Alerta de Inventario",
                    "body"         => $mensaje,
                    "image"        => $imagen
                        ? asset('storage/' . $imagen)
                        : "",
                ],

                "android" => [

                    "priority" => "HIGH",

                    "notification" => [
                        "channel_id"   => "high_importance_channel",
                        "sound"        => "default",
                        "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                    ]
                ]
            ]
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type'  => 'application/json',
        ])->post($url, $payload);

        Log::info("📤 Firebase HTTP: " . $response->status());

        Log::info("📤 Firebase Response:", [
            'body' => $response->body()
        ]);

        if (!$response->successful()) {

            Log::error("❌ Firebase Error: " . $response->body());

        }
    }
}