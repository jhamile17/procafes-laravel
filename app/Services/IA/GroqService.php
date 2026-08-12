<?php

namespace App\Services\IA;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
{
    private string $apiKey;

    private string $model;

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key');
        $this->model = config('services.groq.model');
    }

    /**
     * Consultar a Groq.
     */
    public function chat(array $messages): string
    {
        try {

            $response = Http::withToken($this->apiKey)
                ->acceptJson()
                ->timeout(30)
                ->post(
                    'https://api.groq.com/openai/v1/chat/completions',
                    [
                        'model' => $this->model,
                        'messages' => $messages,
                        'temperature' => 0.3,
                        'max_tokens' => 500,
                    ]
                );

            if ($response->failed()) {

                Log::error(
                    'Groq Error',
                    [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]
                );

                return 'Lo siento, en este momento no puedo responder esa consulta.';
            }

            return trim(
                $response->json(
                    'choices.0.message.content',
                    'No pude generar una respuesta.'
                )
            );

        } catch (Exception $e) {

            Log::error(
                'Groq Exception',
                [
                    'message' => $e->getMessage(),
                ]
            );

            return 'Ocurrió un problema al comunicarme con la IA. Inténtalo nuevamente.';
        }
    }
}