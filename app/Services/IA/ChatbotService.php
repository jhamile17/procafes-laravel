<?php

namespace App\Services\IA;

use Illuminate\Support\Facades\Session;

class ChatbotService
{
    public function __construct(
        protected IntentService $intent,
        protected ProductService $products,
        protected BusinessService $business,
        protected PromptService $prompt,
        protected GroqService $groq,
        protected ResponseService $response
    ) {
    }

    /**
     * Respuesta principal del chatbot.
     */
    public function reply(string $message): array
    {
        /*
        |--------------------------------------------------------------------------
        | Detectar intención
        |--------------------------------------------------------------------------
        */

        $intent = $this->intent->detect($message);

        /*
        |--------------------------------------------------------------------------
        | Ejecutar intención
        |--------------------------------------------------------------------------
        */

        switch ($intent['module']) {

            /*
            |--------------------------------------------------------------------------
            | Saludo
            |--------------------------------------------------------------------------
            */

            case 'greeting':

                return [
                    'message' => '¡Hola! 😊 ¿En qué puedo ayudarte hoy?',
                    'products' => []
                ];


            /*
            |--------------------------------------------------------------------------
            | Información del negocio
            |--------------------------------------------------------------------------
            */

            case 'business':

                return $this->business->answer(
                    $intent['action'] ?? ''
                );


            /*
            |--------------------------------------------------------------------------
            | Productos
            |--------------------------------------------------------------------------
            */

            case 'product':

                /*
                |------------------------------------------------------------------
                | Acciones especiales
                |------------------------------------------------------------------
                */

                if (isset($intent['action'])) {

                    $response = match ($intent['action']) {

                        'cheapest' =>
                            $this->products->cheapest(),

                        'expensive' =>
                            $this->products->expensive(),

                        'available' =>
                            $this->products->available(),

                        'best_sellers' =>
                            $this->products->bestSellers(),

                        default => [
                            'message' => 'No entendí la consulta.',
                            'products' => []
                        ]

                    };

                    /*
                    |--------------------------------------------------------------
                    | Guardar resultado
                    |--------------------------------------------------------------
                    */

                    $this->rememberProducts($response);

                    return $response;
                }

                /*
                |------------------------------------------------------------------
                | Búsqueda normal
                |------------------------------------------------------------------
                */

                $filters = $intent['filters'] ?? [];

                $response = $this->products->search(
                    $filters
                );

                $this->rememberProducts(
                    $response,
                    $filters
                );

                return $response;


            /*
            |--------------------------------------------------------------------------
            | Carrito
            |--------------------------------------------------------------------------
            */

            case 'cart':

                $product = Session::get(
                    'chatbot.selected_product'
                );

                if (!$product) {

                    return [
                        'message' =>
                            'Primero selecciona un producto.',
                        'products' => []
                    ];
                }

                return [

                    'message' =>
                        "Puedes agregar {$product['name']} usando el botón 🛒 que aparece debajo del producto.",

                    'products' => [
                        $product
                    ]

                ];


            /*
            |--------------------------------------------------------------------------
            | Recomendaciones
            |--------------------------------------------------------------------------
            */

            case 'recommendation':

                $filters = $intent['filters'] ?? [];

                $response = $this->products->recommend(
                    $filters
                );

                $this->rememberProducts(
                    $response,
                    $filters
                );

                return $response;


            /*
            |--------------------------------------------------------------------------
            | Acompañamiento
            |--------------------------------------------------------------------------
            */

            case 'companion':

                $response = $this->products->companion();

                $this->rememberProducts(
                    $response
                );

                return $response;


            /*
            |--------------------------------------------------------------------------
            | Conversación anterior
            |--------------------------------------------------------------------------
            */

            case 'conversation':

                return [

                    'message' =>
                        'Estoy recordando tu búsqueda anterior.',

                    'products' =>
                        $this->lastProducts()

                ];


            /*
            |--------------------------------------------------------------------------
            | IA general
            |--------------------------------------------------------------------------
            */

            case 'ai':

            default:

                $messages = $this->prompt->build(
                    collect(),
                    $message
                );

                return [

                    'message' =>
                        $this->groq->chat($messages),

                    'products' => []

                ];
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Guardar productos encontrados
    |--------------------------------------------------------------------------
    */

    private function rememberProducts(
        array $response,
        array $filters = []
    ): void {

        /*
        |----------------------------------------------------------------------
        | Guardar filtros
        |----------------------------------------------------------------------
        */

        Session::put(
            'chatbot.last_filters',
            $filters
        );


        /*
        |----------------------------------------------------------------------
        | Guardar productos
        |----------------------------------------------------------------------
        */

        $products = $response['products'] ?? [];

        Session::put(
            'chatbot.last_products',
            $products
        );


        /*
        |----------------------------------------------------------------------
        | Seleccionar automáticamente el primer producto
        |----------------------------------------------------------------------
        */

        if (!empty($products)) {

            Session::put(
                'chatbot.selected_product',
                $products[0]
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Últimos productos
    |--------------------------------------------------------------------------
    */

    private function lastProducts(): array
    {
        return Session::get(
            'chatbot.last_products',
            []
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Últimos filtros
    |--------------------------------------------------------------------------
    */

    private function lastFilters(): array
    {
        return Session::get(
            'chatbot.last_filters',
            []
        );
    }
}