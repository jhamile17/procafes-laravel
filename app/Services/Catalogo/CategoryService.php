<?php

declare(strict_types=1);

namespace App\Services\Catalogo;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CategoryService
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
    | Crear categoría
    |--------------------------------------------------------------------------
    */

    public function crear(array $datos): Category
    {
        return DB::transaction(function () use ($datos) {

            return Category::create([
                'name' => $datos['name'],
                'slug' => $datos['slug'],
                'description' => $datos['description'] ?? null,
                'status' => $datos['status'] ?? true,
            ]);

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar categoría
    |--------------------------------------------------------------------------
    */

    public function actualizar(
        Category $category,
        array $datos
    ): Category {

        DB::transaction(function () use ($category, $datos) {

            $category->update([
                'name' => $datos['name'],
                'slug' => $datos['slug'],
                'description' => $datos['description'] ?? null,
                'status' => $datos['status'] ?? $category->status,
            ]);

        });

        return $category->fresh();
    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar categoría
    |--------------------------------------------------------------------------
    */

    public function eliminar(Category $category): bool
    {
        if (! $category->delete()) {
            throw new RuntimeException(
                'No fue posible eliminar la categoría.'
            );
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener categoría
    |--------------------------------------------------------------------------
    */

    public function obtener(int $id): Category
    {
        return Category::query()
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener todas las categorías
    |--------------------------------------------------------------------------
    */

    public function obtenerTodos(): Collection
    {
        return Category::query()
            ->orderBy('name')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener categorías activas
    |--------------------------------------------------------------------------
    */

    public function obtenerActivas(): Collection
    {
        return Category::query()
            ->activos()
            ->orderBy('name')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Buscar categorías
    |--------------------------------------------------------------------------
    */

    public function buscar(string $busqueda): Collection
    {
        return Category::query()

            ->where('name', 'like', "%{$busqueda}%")

            ->orWhere('slug', 'like', "%{$busqueda}%")

            ->orWhere('description', 'like', "%{$busqueda}%")

            ->orderBy('name')

            ->get();
    }
}