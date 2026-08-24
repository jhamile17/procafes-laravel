<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    /**
     * Lista de marcas
     */
    public function index(): View
    {
        $brands = Brand::query()
            ->orderBy('name')
            ->paginate(10);

        return view(
            'admin.brands.index',
            compact('brands')
        );
    }


    /**
     * Formulario crear
     */
    public function create(): View
    {
        return view('admin.brands.create');
    }


    /**
     * Guardar marca
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
                'unique:brands,name',
            ],

            'description' => [
                'nullable',
                'string',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | SLUG
        |--------------------------------------------------------------------------
        */

        $validated['slug'] = str($validated['name'])->slug();

        /*
        |--------------------------------------------------------------------------
        | NUEVA MARCA = ACTIVA
        |--------------------------------------------------------------------------
        */

        $validated['status'] = true;

        Brand::create($validated);

        return redirect()
            ->route('admin.brands.index')
            ->with(
                'success',
                'Marca creada correctamente.'
            );
    }


    /**
     * Formulario editar
     */
    public function edit(Brand $brand): View
    {
        return view(
            'admin.brands.edit',
            compact('brand')
        );
    }


    /**
     * Actualizar marca
     */
    public function update(
        Request $request,
        Brand $brand
    ): RedirectResponse {

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands', 'name')
                    ->ignore($brand->id),
            ],

            'description' => [
                'nullable',
                'string',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR SLUG
        |--------------------------------------------------------------------------
        */

        $validated['slug'] = str($validated['name'])->slug();

        /*
        |--------------------------------------------------------------------------
        | NO MODIFICAMOS STATUS
        |--------------------------------------------------------------------------
        */

        $brand->update($validated);

        return redirect()
            ->route('admin.brands.index')
            ->with(
                'success',
                'Marca actualizada correctamente.'
            );
    }


    /**
     * Activar / Desactivar marca
     */
    public function toggleStatus(
        Brand $brand
    ): RedirectResponse {

        try {

            /*
            |--------------------------------------------------------------------------
            | MARCA ACTIVA → DESACTIVAR
            |--------------------------------------------------------------------------
            */

            if ($brand->status) {

                $brand->update([
                    'status' => false,
                ]);

                return redirect()
                    ->route('admin.brands.index')
                    ->with(
                        'success',
                        'Marca desactivada correctamente.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | MARCA INACTIVA → ACTIVAR
            |--------------------------------------------------------------------------
            */

            $brand->update([
                'status' => true,
            ]);

            return redirect()
                ->route('admin.brands.index')
                ->with(
                    'success',
                    'Marca activada correctamente.'
                );

        } catch (\Throwable $e) {

            report($e);

            return redirect()
                ->route('admin.brands.index')
                ->with(
                    'error',
                    'No fue posible cambiar el estado de la marca.'
                );
        }
    }
}