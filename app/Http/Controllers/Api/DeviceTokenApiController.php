<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;

class DeviceTokenApiController extends Controller
{
    /**
     * Registrar o actualizar el token del dispositivo
     * del administrador autenticado.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'device_token' => [
                'required',
                'string',
            ],
        ]);

        // El usuario se obtiene del token de Sanctum.
        $user = $request->user();

        // La aplicación móvil es exclusivamente administrativa.
        if (!$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado.',
            ], 403);
        }

        $device = DeviceToken::updateOrCreate(
            [
                'device_token' => $validated['device_token'],
            ],
            [
                'user_id' => $user->id,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Token registrado correctamente.',
            'data' => $device,
        ], 200);
    }

    /**
     * Obtener los tokens del administrador autenticado.
     */
    public function getTokensByUser(Request $request, $userId)
    {
        $user = $request->user();

        if (!$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado.',
            ], 403);
        }

        // No permitimos consultar tokens de otro usuario.
        if ((int) $user->id !== (int) $userId) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes consultar los tokens de otro usuario.',
            ], 403);
        }

        $tokens = DeviceToken::where('user_id', $user->id)
            ->pluck('device_token');

        return response()->json([
            'success' => true,
            'tokens' => $tokens,
        ], 200);
    }

    /**
     * Eliminar el token del dispositivo.
     */
    public function deleteToken(Request $request)
    {
        $validated = $request->validate([
            'device_token' => [
                'required',
                'string',
            ],
        ]);

        $user = $request->user();

        if (!$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado.',
            ], 403);
        }

        $deleted = DeviceToken::where('device_token', $validated['device_token'])
            ->where('user_id', $user->id)
            ->delete();

        return response()->json([
            'success' => $deleted > 0,
            'message' => $deleted > 0
                ? 'Token eliminado correctamente.'
                : 'Token no encontrado.',
        ], 200);
    }
}