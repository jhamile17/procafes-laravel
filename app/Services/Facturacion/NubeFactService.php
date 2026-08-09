<?php

declare(strict_types=1);

namespace App\Services\Facturacion;

use App\Models\Comprobante;
use App\Models\ElectronicDocument;
use App\Services\Facturacion\Client\ClienteNubeFactService;
use App\Services\Facturacion\DTO\NubeFactResponseDTO;
use App\Services\Facturacion\Exceptions\NubeFactException;
use App\Services\Facturacion\Payload\NubeFactPayloadBuilder;
use Illuminate\Support\Facades\DB;
use Throwable;

final class NubeFactService
{
    public function __construct(
        protected ClienteNubeFactService $cliente,
        protected NubeFactPayloadBuilder $builder,
        protected ElectronicDocumentService $electronicDocumentService,
        protected ComprobanteService $comprobanteService,

    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Emitir comprobante
    |--------------------------------------------------------------------------
    */

    public function emitir(
        Comprobante $comprobante
    ): ElectronicDocument {

        return DB::transaction(function () use ($comprobante) {

            $comprobante->loadMissing([
                'order.user',
                'order.items.product',
            ]);

            $payload = $this->builder->build(
                $comprobante
            );
            $response = $this->cliente->enviar(
                $payload
            );
            dd($response);
            $dto = NubeFactResponseDTO::fromArray(
                $response
            );

            if (! $dto->aceptada()) {

                throw new NubeFactException(
                    $dto->mensaje()
                );
            }

            $documento = $this->electronicDocumentService
                ->crear(
                    $comprobante,
                    $dto
                );

            $this->comprobanteService
                ->marcarEmitido(
                    $comprobante
                );

            return $documento;

        });
    }
}