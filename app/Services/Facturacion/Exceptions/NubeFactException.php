<?php
declare(strict_types=1);
namespace App\Services\Facturacion\Exceptions;
use Exception;
final class NubeFactException extends Exception
{
    /*Constructor*/
    public function __construct(
        string $message = 'Ha ocurrido un error al procesar el comprobante electrónico.',
        int $code = 0,
        ?Exception $previous = null
    ) {
        parent::__construct(
            message: $message,
            code: $code,
            previous: $previous
        );
    }
}