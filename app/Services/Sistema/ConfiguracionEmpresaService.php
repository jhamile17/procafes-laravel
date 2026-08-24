<?php

declare(strict_types=1);

namespace App\Services\Sistema;

use App\Models\ConfiguracionEmpresa;
use App\Models\HorarioEmpresa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ConfiguracionEmpresaService
{
    /*
    |--------------------------------------------------------------------------
    | Días de la semana
    |--------------------------------------------------------------------------
    */

    private array $diasSemana = [
        'lunes',
        'martes',
        'miercoles',
        'jueves',
        'viernes',
        'sabado',
        'domingo',
    ];


    /*
    |--------------------------------------------------------------------------
    | Obtener configuración
    |--------------------------------------------------------------------------
    */

    public function obtener(): ConfiguracionEmpresa
    {
        $configuracion = ConfiguracionEmpresa::query()
            ->with('horarios')
            ->first();

        if (! $configuracion) {

            throw new RuntimeException(
                'No existe una configuración registrada para la empresa.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Crear horarios iniciales
        |--------------------------------------------------------------------------
        */

        $this->crearHorariosIniciales($configuracion);

        return $configuracion->fresh('horarios');
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


            /*
            |--------------------------------------------------------------------------
            | Datos generales
            |--------------------------------------------------------------------------
            */

            $configuracion->update([

                'correo' => $datos['correo'],

                'telefono' => $datos['telefono'],

                'direccion' => $datos['direccion'],

                /*
                |--------------------------------------------------------------------------
                | Compatibilidad con horario general anterior
                |--------------------------------------------------------------------------
                */

                'hora_apertura' =>
                    $datos['hora_apertura']
                    ?? $configuracion->hora_apertura,

                'hora_cierre' =>
                    $datos['hora_cierre']
                    ?? $configuracion->hora_cierre,

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


            /*
            |--------------------------------------------------------------------------
            | Actualizar horarios por día
            |--------------------------------------------------------------------------
            */

            if (
                isset($datos['horarios']) &&
                is_array($datos['horarios'])
            ) {

                foreach ($this->diasSemana as $dia) {

                    $horario = $datos['horarios'][$dia] ?? null;

                    if (! is_array($horario)) {
                        continue;
                    }

                    HorarioEmpresa::updateOrCreate(

                        /*
                        |--------------------------------------------------------------------------
                        | Buscar
                        |--------------------------------------------------------------------------
                        */

                        [
                            'configuracion_empresa_id' =>
                                $configuracion->id,

                            'dia' =>
                                $dia,
                        ],

                        /*
                        |--------------------------------------------------------------------------
                        | Actualizar
                        |--------------------------------------------------------------------------
                        */

                        [
                            'hora_apertura' =>
                                $horario['hora_apertura'] ?? null,

                            'hora_cierre' =>
                                $horario['hora_cierre'] ?? null,

                            'activo' =>
                                (bool) ($horario['activo'] ?? false),
                        ]
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Retornar configuración actualizada
            |--------------------------------------------------------------------------
            */

            return $configuracion->fresh('horarios');
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Crear horarios iniciales
    |--------------------------------------------------------------------------
    */

    private function crearHorariosIniciales(
        ConfiguracionEmpresa $configuracion
    ): void {

        foreach ($this->diasSemana as $dia) {

            HorarioEmpresa::firstOrCreate(

                [
                    'configuracion_empresa_id' =>
                        $configuracion->id,

                    'dia' =>
                        $dia,
                ],

                [
                    'hora_apertura' =>
                        $configuracion->hora_apertura
                        ?? '08:00:00',

                    'hora_cierre' =>
                        $configuracion->hora_cierre
                        ?? '23:00:00',

                    'activo' =>
                        true,
                ]
            );
        }
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
    | Horarios por día
    |--------------------------------------------------------------------------
    */

    public function horarios(): array
    {
        $configuracion = $this->obtener();

        return $configuracion
            ->horarios
            ->keyBy('dia')
            ->toArray();
    }


    /*
    |--------------------------------------------------------------------------
    | Horario general
    |--------------------------------------------------------------------------
    */

    public function horario(): string
    {
        $configuracion = $this->obtener();

        $diaActual = strtolower(
            Carbon::now()->locale('es')->dayName
        );

        $horario = $configuracion
            ->horarios
            ->firstWhere('dia', $diaActual);

        if (! $horario) {
            return 'Horario no configurado';
        }

        if (! $horario->activo) {
            return 'Hoy no atendemos';
        }

        $apertura = Carbon::parse(
            $horario->hora_apertura
        )->format('g:i a');

        $cierre = Carbon::parse(
            $horario->hora_cierre
        )->format('g:i a');

        return "{$apertura} - {$cierre}";
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

            'horarios' =>
                $configuracion
                    ->horarios
                    ->keyBy('dia')
                    ->toArray(),

        ];
    }
}