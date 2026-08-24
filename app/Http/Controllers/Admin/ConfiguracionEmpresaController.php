<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Sistema\ConfiguracionEmpresaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ConfiguracionEmpresaController extends Controller
{
    public function __construct(
        private readonly ConfiguracionEmpresaService $configuracionService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Mostrar configuración
    |--------------------------------------------------------------------------
    */

    public function index(): View
    {
        return view(
            'admin.sistema.configuracion-empresa',
            [
                'configuracion' => $this->configuracionService->obtener(),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar configuración
    |--------------------------------------------------------------------------
    */

    public function update(Request $request): RedirectResponse
    {
        $datos = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Datos institucionales NO modificables
            |--------------------------------------------------------------------------
            |
            | nombre_empresa y ruc no se reciben desde el formulario.
            | Se mantienen únicamente como información de la empresa.
            |
            */

            'correo' => [
                'required',
                'email',
                'max:150',
            ],

            'telefono' => [
                'required',
                'string',
                'max:20',
            ],

            'direccion' => [
                'required',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Horario de atención
            |--------------------------------------------------------------------------
            */

            'horarios' => [
                'nullable',
                'array',
            ],

            'horarios.*.hora_apertura' => [
                'nullable',
                'date_format:H:i',
            ],

            'horarios.*.hora_cierre' => [
                'nullable',
                'date_format:H:i',
            ],

            'horarios.*.activo' => [
                'nullable',
                'boolean',
            ],

            /*
            |--------------------------------------------------------------------------
            | Redes sociales
            |--------------------------------------------------------------------------
            */

            'facebook' => [
                'nullable',
                'url',
                'max:255',
            ],

            'instagram' => [
                'nullable',
                'url',
                'max:255',
            ],

            'tiktok' => [
                'nullable',
                'url',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Logo
            |--------------------------------------------------------------------------
            */

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

        ]);

        $configuracion = $this->configuracionService->obtener();

        /*
        |--------------------------------------------------------------------------
        | Subir logo
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {

            if (
                $configuracion->logo &&
                Storage::disk('public')->exists($configuracion->logo)
            ) {

                Storage::disk('public')->delete(
                    $configuracion->logo
                );
            }

            $datos['logo'] = $request
                ->file('logo')
                ->store(
                    'configuracion',
                    'public'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Actualizar configuración
        |--------------------------------------------------------------------------
        */

        $this->configuracionService->actualizar(
            $datos
        );

        return redirect()
            ->route('admin.configuracion.index')
            ->with(
                'success',
                'La configuración de la empresa se actualizó correctamente.'
            );
    }
}