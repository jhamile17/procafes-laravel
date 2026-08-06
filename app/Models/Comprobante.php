<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Comprobante extends Model
{
    use HasFactory;
    protected $table = 'comprobantes';
    /*
    |--------------------------------------------------------------------------
    | Tipos de comprobante
    |--------------------------------------------------------------------------
    */

    public const BOLETA = 'BOLETA';

    public const FACTURA = 'FACTURA';

    /*
    |--------------------------------------------------------------------------
    | Tipos de documento
    |--------------------------------------------------------------------------
    */

    public const DNI = 'DNI';

    public const RUC = 'RUC';

    /*
    |--------------------------------------------------------------------------
    | Asignación masiva
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'order_id',

        'estado_comprobante_id',

        'tipo_comprobante',

        'tipo_documento',

        'numero_documento',

        'nombre',

        'razon_social',

        'direccion_fiscal',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function estadoComprobante(): BelongsTo
    {
        return $this->belongsTo(
            EstadoComprobante::class
        );
    }

    public function electronicDocument(): HasOne
    {
        return $this->hasOne(
            ElectronicDocument::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function esBoleta(): bool
    {
        return $this->tipo_comprobante === self::BOLETA;
    }

    public function esFactura(): bool
    {
        return $this->tipo_comprobante === self::FACTURA;
    }

    public function usaDni(): bool
    {
        return $this->tipo_documento === self::DNI;
    }

    public function usaRuc(): bool
    {
        return $this->tipo_documento === self::RUC;
    }

    public function denominacion(): string
    {
        return $this->esFactura()

            ? (string) $this->razon_social

            : (string) $this->nombre;
    }

    public function estaPendiente(): bool
    {
        return $this->estadoComprobante?->esPendiente() ?? false;
    }

    public function estaEmitido(): bool
    {
        return $this->estadoComprobante?->esEmitido() ?? false;
    }

    public function estaAnulado(): bool
    {
        return $this->estadoComprobante?->esAnulado() ?? false;
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar estado
    |--------------------------------------------------------------------------
    */

    public function actualizarEstado(
        EstadoComprobante $estado
    ): void {

        $this->update([

            'estado_comprobante_id' => $estado->id,

        ]);

    }
}