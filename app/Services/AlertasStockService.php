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
        // Si el stock está por encima del mínimo,
        // no se genera ninguna alerta.
        if ($product->stock > $product->stock_minimo) {
            return;
        }

        // Determinar tipo de alerta
        $tipo = $product->stock <= 0
            ? 'agotado'
            : 'bajo';

        // Mensaje de alerta
        $mensaje = $tipo === 'agotado'
            ? "Producto AGOTADO: {$product->name}"
            : "Stock bajo ({$product->stock}): {$product->name}";

        // Obtener nivel de alerta
        $nivel = \App\Models\NivelAlertaStock::where(
            'codigo',
            $tipo === 'agotado' ? 'OUT' : 'LOW'
        )->first();

        if (!$nivel) {
            Log::error(
                '❌ No existe el nivel de alerta para: ' . $tipo
            );

            return;
        }

        // =========================================================
        // EVITAR ALERTAS REPETIDAS DURANTE 30 MINUTOS
        // =========================================================

        $ultimaAlerta = AlertaStock::where(
            'product_id',
            $product->id
        )
            ->latest()
            ->first();

        if ($ultimaAlerta) {

            $minutos = Carbon::parse(
                $ultimaAlerta->created_at
            )->diffInMinutes(now());

            if ($minutos < 30) {

                Log::info(
                    "⏳ Alerta omitida. "
                    . "El producto {$product->id} "
                    . "ya tiene una alerta reciente."
                );

                return;
            }
        }

        // =========================================================
        // GUARDAR ALERTA EN BASE DE DATOS
        // =========================================================

        AlertaStock::create([
            'product_id'      => $product->id,
            'nivel_alerta_id' => $nivel->id,
            'stock_detectado' => $product->stock,
            'mensaje'         => $mensaje,
            'enviado_correo'  => false,
            'enviado_app'     => false,
        ]);

        Log::info(
            "🆕 Alerta registrada: {$mensaje}"
        );

        // =========================================================
        // OBTENER TOKENS DE LOS DISPOSITIVOS
        // =========================================================

        $tokens = DeviceToken::where(
            'activo',
            true
        )->pluck('token');

        Log::info(
            "📱 Tokens encontrados: "
            . $tokens->count()
        );

        // =========================================================
        // ENVIAR NOTIFICACIÓN A CADA DISPOSITIVO
        // =========================================================

        foreach ($tokens as $token) {

            try {

                $this->enviarNotificacion(
                    $token,
                    $mensaje,
                    $tipo,
                    $product->id,
                    $product->name,
                    $product->stock,
                    $product->stock_minimo,
                    $product->image
                );

            } catch (\Exception $e) {

                Log::error(
                    "❌ Error enviando notificación: "
                    . $e->getMessage()
                );
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
        $stockMinimo,
        $imagen = null
    ) {

        // =========================================================
        // OBTENER ACCESS TOKEN DE FIREBASE
        // =========================================================

        $firebase = new FirebaseService();

        $accessToken = $firebase->getAccessToken();

        // =========================================================
        // URL FCM HTTP V1
        // =========================================================

        $url =
            "https://fcm.googleapis.com/v1/projects/"
            . "my-project-de-entrega/messages:send";

        // =========================================================
        // PAYLOAD
        // =========================================================

        $payload = [

            "message" => [

                // Token del dispositivo
                "token" => $token,

                // -------------------------------------------------
                // NOTIFICACIÓN VISIBLE
                // -------------------------------------------------

                "notification" => [
                    "title" => "🚨 Alerta de Inventario",
                    "body"  => $mensaje,
                ],

                // -------------------------------------------------
                // DATOS QUE RECIBIRÁ FLUTTER
                // -------------------------------------------------

                "data" => [

                    "tipo" => "producto",

                    "nivel" => $tipo,

                    "producto_id" =>
                        (string) $productoId,

                    "producto" =>
                        (string) $productoName,

                    "stock" =>
                        (string) $stock,

                    "stock_minimo" =>
                        (string) $stockMinimo,

                    "title" =>
                        "🚨 Alerta de Inventario",

                    "body" =>
                        (string) $mensaje,

                    "image" => $imagen
                        ? asset(
                            'storage/' . $imagen
                        )
                        : "",
                ],

                // -------------------------------------------------
                // CONFIGURACIÓN ANDROID
                // -------------------------------------------------

                "android" => [

                    "priority" => "HIGH",

                    "notification" => [

                        "channel_id" =>
                            "high_importance_channel",

                        "sound" =>
                            "default",

                        "click_action" =>
                            "FLUTTER_NOTIFICATION_CLICK",
                    ],
                ],
            ],
        ];

        // =========================================================
        // ENVIAR A FIREBASE
        // =========================================================

        $response = Http::withHeaders([
            'Authorization' =>
                'Bearer ' . $accessToken,

            'Content-Type' =>
                'application/json',

        ])->post(
            $url,
            $payload
        );

        // =========================================================
        // LOG DE RESPUESTA
        // =========================================================

        Log::info(
            "📤 Firebase HTTP: "
            . $response->status()
        );

        Log::info(
            "📤 Firebase Response:",
            [
                'body' =>
                    $response->body(),
            ]
        );

        // =========================================================
        // ERROR
        // =========================================================

        if (!$response->successful()) {

            Log::error(
                "❌ Firebase Error: "
                . $response->body()
            );

        } else {

            Log::info(
                "✅ Notificación enviada correctamente "
                . "al dispositivo."
            );
        }
    }
}