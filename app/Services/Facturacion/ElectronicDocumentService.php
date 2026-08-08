<?php

declare(strict_types=1);

namespace App\Services\Facturacion;

use App\Models\Comprobante;
use App\Models\ElectronicDocument;
use App\Services\Facturacion\DTO\NubeFactResponseDTO;
use RuntimeException;

final class ElectronicDocumentService
{
    /*
    |--------------------------------------------------------------------------
    | Crear documento electrónico
    |--------------------------------------------------------------------------
    */

    public function crear(
        Comprobante $comprobante,
        NubeFactResponseDTO $dto
    ): ElectronicDocument {

        if ($comprobante->electronicDocument()->exists()) {

            throw new RuntimeException(
                'El comprobante ya tiene un documento electrónico.'
            );

        }

        return ElectronicDocument::create([

            'comprobante_id' => $comprobante->id,

            'serie' => $dto->serie(),

            'numero' => $dto->numero(),

            'estado' => ElectronicDocument::ACEPTADO,

            'observacion' => $dto->descripcion(),

            'pdf_url' => $dto->pdf(),

            'xml_url' => $dto->xml(),

            'cdr_url' => $dto->cdr(),

            'response' => $dto->response(),

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar documento
    |--------------------------------------------------------------------------
    */

    public function actualizar(
        ElectronicDocument $document,
        NubeFactResponseDTO $dto
    ): ElectronicDocument {

        $document->update([

            'serie' => $dto->serie(),

            'numero' => $dto->numero(),

            'estado' => ElectronicDocument::ACEPTADO,

            'observacion' => $dto->descripcion(),

            'pdf_url' => $dto->pdf(),

            'xml_url' => $dto->xml(),

            'cdr_url' => $dto->cdr(),

            'response' => $dto->response(),

        ]);

        return $document->refresh();
    }
}