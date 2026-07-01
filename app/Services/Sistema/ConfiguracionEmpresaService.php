<?php

declare(strict_types=1);

namespace App\Services\Sistema;

use App\Models\ConfiguracionEmpresa;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ConfiguracionEmpresaService
{
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener configuración de la empresa
    |--------------------------------------------------------------------------
    */

    public function obtener(): ConfiguracionEmpresa
    {
        $configuracion = ConfiguracionEmpresa::query()->first();

        if (! $configuracion) {

            throw new RuntimeException(
                'No existe una configuración registrada para la empresa.'
            );

        }

        return $configuracion;
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar configuración de la empresa
    |--------------------------------------------------------------------------
    */

    public function actualizar(
        array $datos
    ): ConfiguracionEmpresa {

        return DB::transaction(function () use ($datos) {

            $configuracion = $this->obtener();

            $configuracion->update([

                'nombre_empresa' => $datos['nombre_empresa'],

                'ruc' => $datos['ruc'],

                'correo' => $datos['correo'],

                'telefono' => $datos['telefono'],

                'direccion' => $datos['direccion'],

                'logo' => $datos['logo'] ?? $configuracion->logo,

                'facebook' => $datos['facebook'] ?? null,

                'instagram' => $datos['instagram'] ?? null,

                'tiktok' => $datos['tiktok'] ?? null,

            ]);

            return $configuracion->fresh();

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener logo de la empresa
    |--------------------------------------------------------------------------
    */

    public function logo(): ?string
    {
        return $this->obtener()->logo;
    }
}