<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\Catalogo\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $categoryService
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Listado
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $categories = $this->categoryService->obtenerTodos();

        return view(
            'admin.categories.categories-index',
            compact('categories')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Formulario crear
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('admin.categories.categories-create');
    }

    /*
    |--------------------------------------------------------------------------
    | Guardar
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name'
            ],
            'description' => [
                'nullable',
                'string'
            ],
        ]);

        // generar slug automático si no viene
        $validated['slug'] = str($validated['name'])->slug();

        $this->categoryService->crear($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | Editar
    |--------------------------------------------------------------------------
    */

    public function edit(Category $category)
    {
        return view(
            'admin.categories.categories-edit',
            compact('category')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name,' . $category->id
            ],
            'description' => [
                'nullable',
                'string'
            ],
        ]);

        $validated['slug'] = str($validated['name'])->slug();

        $this->categoryService->actualizar($category, $validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar
    |--------------------------------------------------------------------------
    */

    public function destroy(Category $category)
    {
        try {

            $this->categoryService->eliminar($category);

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Categoría eliminada correctamente.');

        } catch (\Throwable $e) {

            return back()->with(
                'error',
                'No se puede eliminar la categoría porque tiene productos asociados.'
            );
        }
    }
}