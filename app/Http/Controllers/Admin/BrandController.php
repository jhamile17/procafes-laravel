<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    /**
     * Lista de marcas
     */
    public function index()
    {
        $brands = Brand::orderBy('name')
            ->paginate(10);

        return view('admin.brands.index', compact('brands'));
    }

    /**
     * Formulario crear
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Guardar marca
     */
    public function store(Request $request)
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

        Brand::create($validated);

        return redirect()
            ->route('admin.brands.index')
            ->with('ok', 'Marca creada correctamente.');
    }

    /**
     * Formulario editar
     */
    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    /**
     * Actualizar marca
     */
    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands', 'name')
                    ->ignore($brand->brand_id, 'brand_id'),
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ]);

        $brand->update($validated);

        return redirect()
            ->route('admin.brands.index')
            ->with('ok', 'Marca actualizada correctamente.');
    }

    /**
     * Eliminar marca
     */
    public function destroy(Brand $brand)
    {
        if ($brand->products()->exists()) {
            return back()->with(
                'error',
                'No se puede eliminar la marca porque tiene productos asociados.'
            );
        }

        $brand->delete();

        return redirect()
            ->route('admin.brands.index')
            ->with('ok', 'Marca eliminada correctamente.');
    }
}