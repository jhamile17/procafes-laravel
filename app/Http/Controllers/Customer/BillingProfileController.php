<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\BillingProfileRequest;
use App\Models\BillingProfile;
use App\Services\Cliente\BillingProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BillingProfileController extends Controller
{
    public function __construct(
        protected BillingProfileService $billingProfileService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Listar perfiles
    |--------------------------------------------------------------------------
    */

    public function index(): JsonResponse
    {
        return response()->json([

            'success' => true,

            'data' => $this->billingProfileService
                ->list(auth()->id()),

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Consultar RUC
    |--------------------------------------------------------------------------
    */

    public function searchRuc(
    Request $request
    ): JsonResponse {

        $request->validate([

            'ruc' => [
                'required',
                'digits:11',
            ],

        ]);

        $result = $this->billingProfileService
            ->searchRuc(
                $request->string('ruc')->toString()
            );

        if (! $result['success']) {

            return response()->json([

                'success' => false,

                'message' => $result['message'] ?? 'No fue posible consultar el RUC.',

                'data' => null,

            ], 422);

        }

        $empresa = $result['data'];

        return response()->json([

            'success' => true,

            'message' => null,

            'data' => [

                'ruc' => $empresa['numero']
                    ?? $empresa['ruc']
                    ?? '',

                'razon_social' => $empresa['nombre_o_razon_social']
                    ?? $empresa['razon_social']
                    ?? '',

                'direccion_fiscal' => $empresa['direccion']
                    ?? '',

            ],

        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Registrar empresa
    |--------------------------------------------------------------------------
    */

    public function store(
    BillingProfileRequest $request
): JsonResponse {

    try {

        $profile = $this->billingProfileService
            ->create(
                auth()->user(),
                $request->validated()
            );

        return response()->json([

            'success' => true,

            'message' => 'Empresa registrada correctamente.',

            'data' => $profile,

        ], 201);

    } catch (\RuntimeException $exception) {

        return response()->json([

            'success' => false,

            'message' => $exception->getMessage(),

        ], 422);

    }

}

    /*
    |--------------------------------------------------------------------------
    | Eliminar empresa
    |--------------------------------------------------------------------------
    */

    public function destroy(
    BillingProfile $billingProfile
): JsonResponse {

    abort_unless(

        $billingProfile->user_id === auth()->id(),

        403

    );

    try {

        $this->billingProfileService
            ->delete($billingProfile);

        return response()->json([

            'success' => true,

            'message' => 'Empresa eliminada correctamente.',

        ]);

    } catch (\RuntimeException $exception) {

        return response()->json([

            'success' => false,

            'message' => $exception->getMessage(),

        ], 422);

    }

}

    /*
    |--------------------------------------------------------------------------
    | Marcar como predeterminada
    |--------------------------------------------------------------------------
    */

 public function setDefault(
    BillingProfile $billingProfile
): JsonResponse {

    abort_unless(

        $billingProfile->user_id === auth()->id(),

        403

    );

    try {

        $profile = $this->billingProfileService
            ->setDefault($billingProfile);

        return response()->json([

            'success' => true,

            'message' => 'Empresa predeterminada actualizada.',

            'data' => $profile,

        ]);

    } catch (\RuntimeException $exception) {

        return response()->json([

            'success' => false,

            'message' => $exception->getMessage(),

        ], 422);

    }

}
}