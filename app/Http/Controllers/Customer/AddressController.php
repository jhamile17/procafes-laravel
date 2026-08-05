<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ShippingAddress;
use App\Services\LocationIQService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function __construct(
        private readonly LocationIQService $locationIQ
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Buscar direcciones
    |--------------------------------------------------------------------------
    */

    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => ['required', 'string', 'min:2'],
        ]);

        return response()->json([

            'success' => true,

            'data' => $this->locationIQ->search(
                $request->string('q')->toString()
            ),

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Guardar dirección
    |--------------------------------------------------------------------------
    */

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([

            'direccion'     => ['required', 'string', 'max:255'],
            'referencia'    => ['nullable', 'string', 'max:255'],

            'departamento'  => ['required', 'string', 'max:100'],
            'provincia'     => ['required', 'string', 'max:100'],
            'distrito'      => ['required', 'string', 'max:100'],

            'latitude'      => ['nullable', 'numeric'],
            'longitude'     => ['nullable', 'numeric'],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Solo una dirección principal
        |--------------------------------------------------------------------------
        */

        ShippingAddress::where(
            'user_id',
            Auth::id()
        )->update([
            'es_principal' => false,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Crear o actualizar
        |--------------------------------------------------------------------------
        */

        $address = ShippingAddress::updateOrCreate(

            [
                'user_id' => Auth::id(),
            ],

            [

                ...$data,

                'alias' => 'Mi dirección',

                'es_principal' => true,

            ]

        );

        return response()->json([

            'success' => true,

            'message' => 'Dirección guardada correctamente.',

            'data' => [

                'id' => $address->id,

                'direccion' => $address->direccion,

                'referencia' => $address->referencia,

                'departamento' => $address->departamento,

                'provincia' => $address->provincia,

                'distrito' => $address->distrito,

                'direccion_completa' => $address->direccion_completa,

            ],

        ]);
    }
}