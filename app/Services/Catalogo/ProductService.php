<?php

namespace App\Services\Catalogo;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService
{
    /*
    |--------------------------------------------------------------------------
    | Crear producto
    |--------------------------------------------------------------------------
    */

    public function crear(array $datos): Product
    {
        return DB::transaction(function () use ($datos) {

            $datos = $this->prepararDatos($datos);

            return Product::create($datos);

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar producto
    |--------------------------------------------------------------------------
    */

    public function actualizar(Product $product, array $datos): Product
    {
        return DB::transaction(function () use ($product, $datos) {

            $datos = $this->prepararDatos($datos, $product);

            $product->update($datos);

            return $product->fresh();

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar producto
    |--------------------------------------------------------------------------
    */

    public function eliminar(Product $product): bool
    {
        return DB::transaction(function () use ($product) {

            return $product->delete();

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener producto por ID
    |--------------------------------------------------------------------------
    */

    public function obtener(int $id): Product
    {
        return $this->consultaBase()
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener todos los productos
    |--------------------------------------------------------------------------
    */

    public function obtenerTodos(): Collection
    {
        return $this->consultaBase()
            ->orderBy('name')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Buscar productos
    |--------------------------------------------------------------------------
    */

    public function buscar(string $texto): Collection
    {
        return $this->consultaBase()

            ->where(function ($query) use ($texto) {

                $query

                    ->where('name', 'like', "%{$texto}%")

                    ->orWhere('description', 'like', "%{$texto}%")

                    ->orWhere('sku', 'like', "%{$texto}%")

                    ->orWhere('barcode', 'like', "%{$texto}%");

            })

            ->orderBy('name')

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener productos activos
    |--------------------------------------------------------------------------
    */

    public function obtenerActivos(): Collection
    {
        return $this->consultaBase()

            ->where('status', true)

            ->orderBy('name')

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener productos disponibles
    |--------------------------------------------------------------------------
    */

    public function obtenerDisponibles(): Collection
    {
        return $this->consultaBase()

            ->where('status', true)

            ->where('stock', '>', 0)

            ->orderBy('name')

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener productos por categoría
    |--------------------------------------------------------------------------
    */

    public function obtenerPorCategoria(int $categoriaId): Collection
    {
        return $this->consultaBase()

            ->where('categories_id', $categoriaId)

            ->orderBy('name')

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener productos por marca
    |--------------------------------------------------------------------------
    */

    public function obtenerPorMarca(int $marcaId): Collection
    {
        return $this->consultaBase()

            ->where('brand_id', $marcaId)

            ->orderBy('name')

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener productos por tipo de consumo
    |--------------------------------------------------------------------------
    */

    public function obtenerPorTipoConsumo(int $tipoConsumoId): Collection
    {
        return $this->consultaBase()

            ->where('tipo_consumo_id', $tipoConsumoId)

            ->orderBy('name')

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener producto por SKU
    |--------------------------------------------------------------------------
    */

    public function obtenerPorSku(string $sku): Product
    {
        return $this->consultaBase()

            ->where('sku', $sku)

            ->firstOrFail();
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener producto por Slug
    |--------------------------------------------------------------------------
    */

    public function obtenerPorSlug(string $slug): Product
    {
        return $this->consultaBase()

            ->where('slug', $slug)

            ->firstOrFail();
    }

    /*
    |--------------------------------------------------------------------------
    | Activar producto
    |--------------------------------------------------------------------------
    */

    public function activar(Product $product): Product
    {
        $product->update([
            'status' => true,
        ]);

        return $product->fresh();
    }

    /*
    |--------------------------------------------------------------------------
    | Desactivar producto
    |--------------------------------------------------------------------------
    */

    public function desactivar(Product $product): Product
    {
        $product->update([
            'status' => false,
        ]);

        return $product->fresh();
    }

    /*
    |--------------------------------------------------------------------------
    | Consulta base
    |--------------------------------------------------------------------------
    |
    | Centraliza todas las relaciones utilizadas por el servicio.
    |
    */

    private function consultaBase(): Builder
    {
        return Product::query()

            ->with([

                'category',

                'brand',

                'tipoConsumo',

                'productImages',

            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Preparar datos
    |--------------------------------------------------------------------------
    */

    private function prepararDatos(
        array $datos,
        ?Product $product = null
    ): array {

        $datos = $this->prepararSlug($datos);

        $datos = $this->prepararSku($datos);

        return $datos;
    }

    /*
    |--------------------------------------------------------------------------
    | Preparar Slug
    |--------------------------------------------------------------------------
    */

    private function prepararSlug(array $datos): array
    {
        if (
            empty($datos['slug']) &&
            !empty($datos['name'])
        ) {

            $datos['slug'] = Str::slug($datos['name']);

        }

        return $datos;
    }

    /*
    |--------------------------------------------------------------------------
    | Preparar SKU
    |--------------------------------------------------------------------------
    */

    private function prepararSku(array $datos): array
    {
        if (empty($datos['sku'])) {

            $datos['sku'] = $this->generarSku();

        }

        return $datos;
    }

    /*
    |--------------------------------------------------------------------------
    | Generar SKU
    |--------------------------------------------------------------------------
    */

    private function generarSku(): string
    {
        do {

            $sku = 'PRO-' . strtoupper(Str::random(8));

        } while (

            Product::where('sku', $sku)->exists()

        );

        return $sku;
    }
}