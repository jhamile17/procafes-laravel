<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\Catalogo\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $categoryService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Listado
    |--------------------------------------------------------------------------
    */

    public function index(): View
    {
        $categories = $this->categoryService->obtenerTodos();

        return view(
            'admin.categories.index',
            compact('categories')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Formulario crear
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {
        return view('admin.categories.create');
    }

    /*
    |--------------------------------------------------------------------------
    | Guardar
    |--------------------------------------------------------------------------
    */

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        $validated['slug'] = str($validated['name'])->slug();

        // Nueva categoría = activa
        $validated['status'] = true;

        $this->categoryService->crear($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Categoría creada correctamente.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Editar
    |--------------------------------------------------------------------------
    */

    public function edit(Category $category): View
    {
        return view(
            'admin.categories.edit',
            compact('category')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Category $category
    ): RedirectResponse {

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name,' . $category->id,
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        $validated['slug'] = str($validated['name'])->slug();

        // No modificamos status al editar
        $this->categoryService->actualizar(
            $category,
            $validated
        );

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'success',
                'Categoría actualizada correctamente.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Activar / Desactivar
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(
        Category $category
    ): RedirectResponse {

        try {

            if ($category->status) {

                $category->update([
                    'status' => false,
                ]);

                return redirect()
                    ->route('admin.categories.index')
                    ->with(
                        'success',
                        'Categoría desactivada correctamente.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | ACTIVAR
            |--------------------------------------------------------------------------
            */

            $category->update([
                'status' => true,
            ]);

            return redirect()
                ->route('admin.categories.index')
                ->with(
                    'success',
                    'Categoría activada correctamente.'
                );

        } catch (\Throwable $e) {

            report($e);

            return redirect()
                ->route('admin.categories.index')
                ->with(
                    'error',
                    'No fue posible cambiar el estado de la categoría.'
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar
    |--------------------------------------------------------------------------
    |
    | Ya no utilizamos eliminación desde el panel.
    |
    */
}