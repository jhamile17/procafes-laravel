<?php

declare(strict_types=1);

namespace App\Services\Cliente;

use App\Services\Integraciones\ApiPeruService;

class DocumentLookupService
{
    public function __construct(
        protected ApiPeruService $apiPeruService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Consultar documento
    |--------------------------------------------------------------------------
    */

    public function consultarDocumento(
        string $numeroDocumento
    ): array {

        $numeroDocumento = trim($numeroDocumento);

        return match (strlen($numeroDocumento)) {

            8 => $this->apiPeruService
                ->consultarDni($numeroDocumento),

            11 => $this->apiPeruService
                ->consultarRuc($numeroDocumento),

            default => [

                'success' => false,

                'message' => 'Número de documento inválido.',

                'data' => null,

            ],

        };

    }

    /*
    |--------------------------------------------------------------------------
    | Es DNI
    |--------------------------------------------------------------------------
    */

    public function esDni(
        string $numeroDocumento
    ): bool {

        return preg_match(
            '/^\d{8}$/',
            $numeroDocumento
        ) === 1;

    }

    /*
    |--------------------------------------------------------------------------
    | Es RUC
    |--------------------------------------------------------------------------
    */

    public function esRuc(
        string $numeroDocumento
    ): bool {

        return preg_match(
            '/^\d{11}$/',
            $numeroDocumento
        ) === 1;

    }
}