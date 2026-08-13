<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AlertaStock;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AlertController extends Controller
{
    /**
     * Obtener el historial de alertas de stock.
     *
     * Este endpoint es utilizado por la aplicación móvil
     * del administrador.
     */
    public function index(): JsonResponse
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | Obtener alertas
            |--------------------------------------------------------------------------
            |
            | Se utiliza created_at porque esa es la fecha real
            | de creación del registro en alertas_stock.
            |
            */

            $alertas = AlertaStock::query()
                ->with([
                    'product',
                    'nivelAlerta',
                ])
                ->orderByDesc('created_at')
                ->get();

            /*
            |--------------------------------------------------------------------------
            | Construir respuesta para Flutter
            |--------------------------------------------------------------------------
            */

            $result = [];

            foreach ($alertas as $alerta) {

                $producto = $alerta->product;

                /*
                | Si el producto fue eliminado, no mostramos
                | la alerta en la aplicación.
                */

                if (!$producto) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Fecha
                |--------------------------------------------------------------------------
                */

                $fecha = '';

                if ($alerta->created_at) {
                    $fecha = Carbon::parse($alerta->created_at)
                        ->setTimezone('America/Lima')
                        ->format('Y-m-d H:i:s');
                }

                /*
                |--------------------------------------------------------------------------
                | Tipo de alerta
                |--------------------------------------------------------------------------
                */

                $tipo = null;

                if ($alerta->nivelAlerta) {
                    $tipo = $alerta->nivelAlerta->codigo;
                }

                /*
                |--------------------------------------------------------------------------
                | Agregar registro
                |--------------------------------------------------------------------------
                */

                $result[] = [

                    // ID del registro de alertas_stock
                    'id' => (int) $alerta->id,

                    // ID del producto
                    'product_id' => (int) $producto->id,

                    // Nombre del producto
                    'name' => $producto->name,

                    // Stock que tenía cuando se generó la alerta
                    'stock' => (int) $alerta->stock_detectado,

                    // Stock mínimo configurado actualmente
                    'stock_minimo' => (int) ($producto->stock_minimo ?? 0),

                    // Imagen
                    'image_url' => $producto->image
                        ? asset('storage/' . $producto->image)
                        : null,

                    // Fecha del registro histórico
                    'fecha' => $fecha,

                    // Mensaje de la alerta
                    'mensaje' => $alerta->mensaje ?? '',

                    // LOW / CRITICAL / OUT
                    'tipo' => $tipo,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Respuesta
            |--------------------------------------------------------------------------
            */

            return response()->json(
                $result,
                200,
                [
                    'Content-Type' => 'application/json; charset=UTF-8',
                ],
                JSON_UNESCAPED_UNICODE
            );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Registrar error
            |--------------------------------------------------------------------------
            */

            Log::error(
                'Error obteniendo historial de alertas para API móvil.',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Respuesta de error
            |--------------------------------------------------------------------------
            */

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error al obtener el historial de alertas.',
                ],
                500,
                [
                    'Content-Type' => 'application/json; charset=UTF-8',
                ],
                JSON_UNESCAPED_UNICODE
            );
        }
    }
}