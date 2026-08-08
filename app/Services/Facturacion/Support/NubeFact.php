<?php

declare(strict_types=1);

namespace App\Services\Facturacion\Support;

final class NubeFact
{
    /*Operaciones*/

    public const GENERAR_COMPROBANTE = 'generar_comprobante';

    /*Comprobantes*/

    public const FACTURA = 1;

    public const BOLETA = 2;

    /*Documento de identidad*/

    public const DNI = 1;

    public const RUC = 6;

    /*Moneda*/

    public const SOLES = 1;

    public const DOLARES = 2;

    /*Tipo de operación SUNAT*/

    public const VENTA_INTERNA = 1;

    /*IGV*/

    public const PORCENTAJE_IGV = 18;

    /*Tipo de afectación IGV*/

    public const GRAVADO = 10;

    /*Unidad de medida*/

    public const UNIDAD = 'NIU';

    /*Forma de pago*/
    
    public const CONTADO = 'Contado';
}