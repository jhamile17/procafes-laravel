<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Credenciales incorrectas',
            ], 401);
        }

        /** @var User $user */
        $user = Auth::user();

        // La aplicación móvil es exclusivamente para administradores
        if (!$user->isAdmin()) {
            Auth::logout();

            return response()->json([
                'message' => 'No autorizado',
            ], 403);
        }

        // Verificar que la cuenta esté activa
        if (!$user->isActive()) {
            Auth::logout();

            return response()->json([
                'message' => 'La cuenta se encuentra inactiva',
            ], 403);
        }

        // Crear token para Flutter mediante Sanctum
        $token = $user
            ->createToken('mobile-token')
            ->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->where(
            'id',
            $request->user()->currentAccessToken()->id
        )->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente',
        ]);
    }
}