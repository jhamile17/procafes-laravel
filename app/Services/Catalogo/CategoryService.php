<?php

declare(strict_types=1);

namespace App\Services\Catalogo;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CategoryService
{
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
                'status' => (bool) ($datos['status'] ?? true),
            ]);

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar categoría
    |--------------------------------------------------------------------------
    */

    public function actualizar(Category $category, array $datos): Category
    {
        return DB::transaction(function () use ($category, $datos) {

            $category->update([
                'name' => $datos['name'],
                'slug' => $datos['slug'],
                'description' => $datos['description'] ?? null,
                'status' => isset($datos['status'])
                    ? (bool) $datos['status']
                    : $category->status,
            ]);

            return $category->refresh();

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar categoría
    |--------------------------------------------------------------------------
    */

    public function eliminar(Category $category): bool
    {
        if (! $category->delete()) {
            throw new RuntimeException('No fue posible eliminar la categoría.');
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
        return Category::findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener todas
    |--------------------------------------------------------------------------
    */

    public function obtenerTodos(): LengthAwarePaginator
    {
        return Category::orderBy('name')
            ->paginate(10)
            ->withQueryString();
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener activas
    |--------------------------------------------------------------------------
    */

    public function obtenerActivas(): Collection
    {
        return Category::where('status', 1)
            ->orderBy('name')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Buscar categorías (CORREGIDO)
    |--------------------------------------------------------------------------
    */

    public function buscar(string $busqueda): Collection
    {
        return Category::query()
            ->where(function ($query) use ($busqueda) {

                $query->where('name', 'like', "%{$busqueda}%")
                    ->orWhere('slug', 'like', "%{$busqueda}%")
                    ->orWhere('description', 'like', "%{$busqueda}%");

            })
            ->orderBy('name')
            ->get();
    }
}