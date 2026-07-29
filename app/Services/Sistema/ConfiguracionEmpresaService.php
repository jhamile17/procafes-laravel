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
    | Actualizar configuración
    |--------------------------------------------------------------------------
    */

    public function actualizar(array $datos): ConfiguracionEmpresa
    {
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
    | Obtener nombre de la empresa
    |--------------------------------------------------------------------------
    */

    public function nombreEmpresa(): string
    {
        return $this->obtener()->nombre_empresa;
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener RUC
    |--------------------------------------------------------------------------
    */

    public function ruc(): string
    {
        return $this->obtener()->ruc;
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener logo
    |--------------------------------------------------------------------------
    */

    public function logo(): ?string
    {
        return $this->obtener()->logo;
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener datos de contacto
    |--------------------------------------------------------------------------
    */

    public function contacto(): array
    {
        $configuracion = $this->obtener();

        return [

            'telefono' => $configuracion->telefono,

            'correo' => $configuracion->correo,

            'direccion' => $configuracion->direccion,

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener redes sociales
    |--------------------------------------------------------------------------
    */

    public function redesSociales(): array
    {
        $configuracion = $this->obtener();

        return [

            'facebook' => $configuracion->facebook,

            'instagram' => $configuracion->instagram,

            'tiktok' => $configuracion->tiktok,

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener información completa para vistas públicas
    |--------------------------------------------------------------------------
    */

    public function informacion(): array
    {
        $configuracion = $this->obtener();

        return [

            'nombre_empresa' => $configuracion->nombre_empresa,

            'ruc' => $configuracion->ruc,

            'correo' => $configuracion->correo,

            'telefono' => $configuracion->telefono,

            'direccion' => $configuracion->direccion,

            'logo' => $configuracion->logo,

            'facebook' => $configuracion->facebook,

            'instagram' => $configuracion->instagram,

            'tiktok' => $configuracion->tiktok,
            'whatsapp' => $configuracion->whatsapp,

        ];
    }
}