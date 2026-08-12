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
     * Obtener historial de alertas para la aplicación móvil.
     */
    public function index(): JsonResponse
    {
        try {
            $alertas = AlertaStock::with('product')
                ->orderByDesc('created_at')
                ->get();

            $result = [];

            foreach ($alertas as $alerta) {

                $producto = $alerta->product;

                // Si el producto ya no existe, omitimos la alerta
                if (!$producto) {
                    continue;
                }

                $result[] = [
                    'id' => $alerta->id,

                    'product_id' => $producto->id,

                    'name' => $producto->name,

                    // Flutter espera "stock"
                    'stock' => (int) $alerta->stock_detectado,

                    // Stock mínimo actual del producto
                    'stock_minimo' => (int) ($producto->stock_minimo ?? 0),

                    'image_url' => $producto->image
                        ? asset('storage/' . $producto->image)
                        : null,

                    // Fecha en que se creó la alerta
                    'fecha' => $alerta->created_at
                        ? Carbon::parse($alerta->created_at)
                            ->setTimezone('America/Lima')
                            ->format('Y-m-d H:i:s')
                        : '',
                ];
            }

            return response()->json($result, 200);

        } catch (\Throwable $e) {

            Log::error(
                'Error obteniendo historial de alertas para API móvil.',
                [
                    'error' => $e->getMessage(),
                ]
            );

            return response()->json([
                'message' => 'Error al obtener las alertas.',
            ], 500);
        }
    }
}