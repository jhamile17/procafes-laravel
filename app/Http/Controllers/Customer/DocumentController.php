<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\Integraciones\ApiPeruService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function __construct(
        protected ApiPeruService $apiPeruService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Consultar DNI
    |--------------------------------------------------------------------------
    */

    public function dni(
        Request $request
    ): JsonResponse {

        $validated = $request->validate([

            'dni' => [

                'required',

                'digits:8',

            ],

        ]);

        $result = $this->apiPeruService
            ->consultarDni(
                $validated['dni']
            );

        return $this->response($result);

    }

    /*
    |--------------------------------------------------------------------------
    | Consultar RUC
    |--------------------------------------------------------------------------
    */

    public function ruc(
        Request $request
    ): JsonResponse {

        $validated = $request->validate([

            'ruc' => [

                'required',

                'digits:11',

            ],

        ]);

        $result = $this->apiPeruService
            ->consultarRuc(
                $validated['ruc']
            );

        return $this->response($result);

    }

    /*
    |--------------------------------------------------------------------------
    | Respuesta estándar
    |--------------------------------------------------------------------------
    */

    protected function response(
        array $result
    ): JsonResponse {

        if (! $result['success']) {

            return response()->json([

                'success' => false,

                'message' => $result['message'],

                'data' => null,

            ], 422);

        }

        return response()->json([

            'success' => true,

            'message' => null,

            'data' => $result['data'],

        ]);

    }
}