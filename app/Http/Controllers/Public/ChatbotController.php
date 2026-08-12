<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\IA\ChatbotService;
use Throwable;

class ChatbotController extends Controller
{
    public function chat(
        Request $request,
        ChatbotService $chatbot
        
    ) {
        $request->validate([
            'mensaje' => ['required', 'string', 'max:500'],
        ]);

        try {

            $response = $chatbot->reply(
                $request->input('mensaje')
            );

            return response()->json($response);

        } catch (Throwable $e) {

            report($e);

            return response()->json([

                'message' =>
                    'Lo siento, ocurrió un error al procesar tu consulta. Inténtalo nuevamente.',

                'products' => []

            ], 500);
        }
    }
}