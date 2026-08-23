<?php

declare(strict_types=1);

namespace App\Services\Sistema;

use App\Models\ConfiguracionEmpresa;
use Carbon\Carbon;
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

                /*
                |--------------------------------------------------------------------------
                | Datos de contacto
                |--------------------------------------------------------------------------
                */

                'correo' =>
                    $datos['correo'],

                'telefono' =>
                    $datos['telefono'],

                'direccion' =>
                    $datos['direccion'],


                /*
                |--------------------------------------------------------------------------
                | Horario
                |--------------------------------------------------------------------------
                */

                'hora_apertura' =>
                    $datos['hora_apertura'],

                'hora_cierre' =>
                    $datos['hora_cierre'],


                /*
                |--------------------------------------------------------------------------
                | Logo
                |--------------------------------------------------------------------------
                */

                'logo' =>
                    $datos['logo']
                    ?? $configuracion->logo,


                /*
                |--------------------------------------------------------------------------
                | Redes sociales
                |--------------------------------------------------------------------------
                */

                'facebook' =>
                    $datos['facebook']
                    ?? null,

                'instagram' =>
                    $datos['instagram']
                    ?? null,

                'tiktok' =>
                    $datos['tiktok']
                    ?? null,

            ]);

            return $configuracion->fresh();

        });
    }


    /*
    |--------------------------------------------------------------------------
    | Nombre de empresa
    |--------------------------------------------------------------------------
    */

    public function nombreEmpresa(): string
    {
        return $this->obtener()->nombre_empresa;
    }


    /*
    |--------------------------------------------------------------------------
    | RUC
    |--------------------------------------------------------------------------
    */

    public function ruc(): string
    {
        return $this->obtener()->ruc;
    }


    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    */

    public function logo(): ?string
    {
        return $this->obtener()->logo;
    }


    /*
    |--------------------------------------------------------------------------
    | Datos de contacto
    |--------------------------------------------------------------------------
    */

    public function contacto(): array
    {
        $configuracion = $this->obtener();

        return [

            'telefono' =>
                $configuracion->telefono,

            'correo' =>
                $configuracion->correo,

            'direccion' =>
                $configuracion->direccion,

            'hora_apertura' =>
                $configuracion->hora_apertura,

            'hora_cierre' =>
                $configuracion->hora_cierre,

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Horario de atención
    |--------------------------------------------------------------------------
    */

    public function horario(): string
    {
        $configuracion = $this->obtener();

        $apertura = Carbon::parse(
            $configuracion->hora_apertura
        )->format('g:i a');

        $cierre = Carbon::parse(
            $configuracion->hora_cierre
        )->format('g:i a');

        return "Lunes a Domingo · {$apertura} - {$cierre}";
    }


    /*
    |--------------------------------------------------------------------------
    | Redes sociales
    |--------------------------------------------------------------------------
    */

    public function redesSociales(): array
    {
        $configuracion = $this->obtener();

        return [

            'facebook' =>
                $configuracion->facebook,

            'instagram' =>
                $configuracion->instagram,

            'tiktok' =>
                $configuracion->tiktok,

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Información completa
    |--------------------------------------------------------------------------
    */

    public function informacion(): array
    {
        $configuracion = $this->obtener();

        return [

            'nombre_empresa' =>
                $configuracion->nombre_empresa,

            'ruc' =>
                $configuracion->ruc,

            'correo' =>
                $configuracion->correo,

            'telefono' =>
                $configuracion->telefono,

            'direccion' =>
                $configuracion->direccion,

            'logo' =>
                $configuracion->logo,

            'facebook' =>
                $configuracion->facebook,

            'instagram' =>
                $configuracion->instagram,

            'tiktok' =>
                $configuracion->tiktok,

            'whatsapp' =>
                $configuracion->whatsapp,

            'hora_apertura' =>
                $configuracion->hora_apertura,

            'hora_cierre' =>
                $configuracion->hora_cierre,

        ];
    }
}