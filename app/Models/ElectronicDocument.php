<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectronicDocument extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Estados
    |--------------------------------------------------------------------------
    */

    public const PENDIENTE = 'PENDIENTE';

    public const ENVIADO = 'ENVIADO';

    public const ACEPTADO = 'ACEPTADO';

    public const RECHAZADO = 'RECHAZADO';

    public const ANULADO = 'ANULADO';

    /*
    |--------------------------------------------------------------------------
    | Tipos
    |--------------------------------------------------------------------------
    */

    public const BOLETA = 'BOLETA';

    public const FACTURA = 'FACTURA';

    /*
    |--------------------------------------------------------------------------
    | Asignación masiva
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'order_id',

        'payment_id',

        'billing_profile_id',

        'tipo',

        'serie',

        'numero',

        'estado',

        'codigo_hash',

        'xml_url',

        'pdf_url',

        'cdr_url',

        'sunat_response',

        'observaciones',

        'fecha_emision',

    ];

    /*
    |--------------------------------------------------------------------------
    | Conversión de atributos
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [

            'order_id' => 'integer',

            'payment_id' => 'integer',

            'billing_profile_id' => 'integer',

            'fecha_emision' => 'datetime',

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function billingProfile(): BelongsTo
    {
        return $this->belongsTo(BillingProfile::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePendientes(
        Builder $query
    ): Builder {

        return $query->where(
            'estado',
            self::PENDIENTE
        );
    }

    public function scopeAceptados(
        Builder $query
    ): Builder {

        return $query->where(
            'estado',
            self::ACEPTADO
        );
    }

    public function scopeFacturas(
        Builder $query
    ): Builder {

        return $query->where(
            'tipo',
            self::FACTURA
        );
    }

    public function scopeBoletas(
        Builder $query
    ): Builder {

        return $query->where(
            'tipo',
            self::BOLETA
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos auxiliares
    |--------------------------------------------------------------------------
    */

    public function estaPendiente(): bool
    {
        return $this->estado === self::PENDIENTE;
    }

    public function estaEnviado(): bool
    {
        return $this->estado === self::ENVIADO;
    }

    public function estaAceptado(): bool
    {
        return $this->estado === self::ACEPTADO;
    }

    public function fueRechazado(): bool
    {
        return $this->estado === self::RECHAZADO;
    }

    public function fueAnulado(): bool
    {
        return $this->estado === self::ANULADO;
    }

    public function puedeEnviarse(): bool
    {
        return $this->estado === self::PENDIENTE;
    }

    public function marcarComoEnviado(): void
    {
        $this->update([
            'estado' => self::ENVIADO,
        ]);
    }
}