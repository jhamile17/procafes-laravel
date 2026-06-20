<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Lista de categorías
     */
    public function index()
    {
        $categories = Category::orderBy('name')
            ->paginate(10);

        return view('admin.categories.categories-index', compact('categories'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        return view('admin.categories.categories-create');
    }

    /**
     * Guardar categoría
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

        Category::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('ok', 'Categoría creada correctamente.');
    }

    /**
     * Formulario editar
     */
    public function edit(Category $category)
    {
        return view(
            'admin.categories.categories-edit',
            compact('category')
        );
    }

    /**
     * Actualizar categoría
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name,' .
                $category->categories_id .
                ',categories_id'
            ],
            'description' => [
                'nullable',
                'string'
            ],
        ]);

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('ok', 'Categoría actualizada correctamente.');
    }

    /**
     * Eliminar categoría
     */
    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with(
                'error',
                'No se puede eliminar la categoría porque tiene productos asociados.'
            );
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('ok', 'Categoría eliminada correctamente.');
    }
}