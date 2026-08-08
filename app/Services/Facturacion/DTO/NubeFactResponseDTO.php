<?php

declare(strict_types=1);

namespace App\Services\Facturacion\Exceptions;

use RuntimeException;

final class NubeFactException extends RuntimeException
{
    public static function respuestaInvalida(): self
    {
        return new self(
            'NubeFact devolvió una respuesta inválida.'
        );
    }

    public static function solicitudFallida(
        string $mensaje
    ): self {

        return new self(
            $mensaje
        );
    }
}