<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectronicDocument extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Estados SUNAT
    |--------------------------------------------------------------------------
    */

    public const PENDIENTE = 'PENDIENTE';

    public const ACEPTADO = 'ACEPTADO';

    public const RECHAZADO = 'RECHAZADO';

    public const ANULADO = 'ANULADO';

    /*
    |--------------------------------------------------------------------------
    | Asignación masiva
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'comprobante_id',

        'serie',

        'numero',

        'estado',

        'observacion',

        'pdf_url',

        'xml_url',

        'cdr_url',

        'response',

    ];

    /*
    |--------------------------------------------------------------------------
    | Conversión de atributos
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [

            'response' => 'array',

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function comprobante(): BelongsTo
    {
        return $this->belongsTo(
            Comprobante::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function esPendiente(): bool
    {
        return $this->estado === self::PENDIENTE;
    }

    public function esAceptado(): bool
    {
        return $this->estado === self::ACEPTADO;
    }

    public function esRechazado(): bool
    {
        return $this->estado === self::RECHAZADO;
    }

    public function esAnulado(): bool
    {
        return $this->estado === self::ANULADO;
    }

    public function tienePdf(): bool
    {
        return ! empty($this->pdf_url);
    }

    public function tieneXml(): bool
    {
        return ! empty($this->xml_url);
    }

    public function tieneCdr(): bool
    {
        return ! empty($this->cdr_url);
    }

    public function numeroCompleto(): string
    {
        return "{$this->serie}-{$this->numero}";
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar estado
    |--------------------------------------------------------------------------
    */

    public function actualizarEstado(
        string $estado,
        ?string $observacion = null
    ): void {

        $this->update([

            'estado' => strtoupper($estado),

            'observacion' => $observacion,

        ]);

    }
}