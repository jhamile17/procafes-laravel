<?php

declare(strict_types=1);

namespace App\Services\Catalogo;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class BrandService
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
    | Crear marca
    |--------------------------------------------------------------------------
    */

    public function crear(array $datos): Brand
    {
        return DB::transaction(function () use ($datos) {

            return Brand::create([
                'name' => $datos['name'],
                'slug' => $datos['slug'],
                'description' => $datos['description'] ?? null,
                'status' => $datos['status'] ?? true,
            ]);

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar marca
    |--------------------------------------------------------------------------
    */

    public function actualizar(
        Brand $brand,
        array $datos
    ): Brand {

        DB::transaction(function () use ($brand, $datos) {

            $brand->update([
                'name'        => $datos['name'],
                'slug'        => $datos['slug'],
                'description' => $datos['description'] ?? null,
                'status'      => $datos['status'] ?? $brand->status,
            ]);

        });

        return $brand->fresh();
    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar marca
    |--------------------------------------------------------------------------
    */

    public function eliminar(Brand $brand): bool
    {
        if (! $brand->delete()) {

            throw new RuntimeException(
                'No fue posible eliminar la marca.'
            );

        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener marca
    |--------------------------------------------------------------------------
    */

    public function obtener(int $id): Brand
    {
        return Brand::query()
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener todas las marcas
    |--------------------------------------------------------------------------
    */

    public function obtenerTodas(): Collection
    {
        return Brand::query()
            ->orderBy('name')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener marcas activas
    |--------------------------------------------------------------------------
    */

    public function obtenerActivas(): Collection
    {
        return Brand::query()
            ->activos()
            ->orderBy('name')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Buscar marcas
    |--------------------------------------------------------------------------
    */

    public function buscar(string $busqueda): Collection
    {
        return Brand::query()

            ->where('name', 'like', "%{$busqueda}%")

            ->orWhere('slug', 'like', "%{$busqueda}%")

            ->orWhere('description', 'like', "%{$busqueda}%")

            ->orderBy('name')

            ->get();
    }
}