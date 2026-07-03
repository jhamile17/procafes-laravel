<?php

namespace App\Services\Catalogo;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService
{
    /*
    |--------------------------------------------------------------------------
    | CRUD
    |--------------------------------------------------------------------------
    */

    public function crear(array $datos): Product
    {
        return DB::transaction(function () use ($datos) {

            $datos = $this->prepararDatos($datos);

            return Product::create($datos);
        });
    }

    public function actualizar(Product $product, array $datos): Product
    {
        return DB::transaction(function () use ($product, $datos) {

            $datos = $this->prepararDatos($datos, $product);

            $product->update($datos);

            return $product->fresh();
        });
    }

    public function eliminar(Product $product): bool
    {
        return DB::transaction(function () use ($product) {

            // eliminar imagen si existe
            if ($product->image) {
                $path = storage_path('app/public/' . $product->image);

                if (file_exists($path)) {
                    unlink($path);
                }
            }

            return (bool) $product->delete();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener productos
    |--------------------------------------------------------------------------
    */

    public function obtener(int $id): Product
    {
        return $this->consultaBase()
            ->findOrFail($id);
    }

    public function obtenerTodos(): Collection
    {
        return $this->consultaBase()
            ->orderBy('name')
            ->get();
    }

    public function obtenerActivos(): Collection
    {
        return $this->consultaBase()
            ->where('status', true)
            ->orderBy('name')
            ->get();
    }

    public function obtenerDisponibles(): Collection
    {
        return $this->consultaBase()
            ->where('status', true)
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();
    }

    public function obtenerPorCategoria(int $categoriaId): Collection
    {
        return $this->consultaBase()
            ->where('categories_id', $categoriaId)
            ->orderBy('name')
            ->get();
    }

    public function obtenerPorMarca(int $marcaId): Collection
    {
        return $this->consultaBase()
            ->where('brand_id', $marcaId)
            ->orderBy('name')
            ->get();
    }

    public function obtenerPorTipoConsumo(int $tipoConsumoId): Collection
    {
        return $this->consultaBase()
            ->where('tipo_consumo_id', $tipoConsumoId)
            ->orderBy('name')
            ->get();
    }

    public function obtenerPorSku(string $sku): Product
    {
        return $this->consultaBase()
            ->where('sku', $sku)
            ->firstOrFail();
    }

    public function obtenerPorSlug(string $slug): Product
    {
        return $this->consultaBase()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    /*
    |--------------------------------------------------------------------------
    | Listado Administración
    |--------------------------------------------------------------------------
    */

    public function listar(array $filtros = []): Builder
    {
        $query = $this->consultaBase();

        $this->aplicarBusqueda(
            $query,
            $filtros['buscar'] ?? null
        );

        $this->aplicarCategoria(
            $query,
            $filtros['categoria'] ?? null
        );

        $this->aplicarMarca(
            $query,
            $filtros['marca'] ?? null
        );

        $this->aplicarTipoConsumo(
            $query,
            $filtros['tipo'] ?? null
        );

        $this->aplicarEstado(
            $query,
            $filtros['estado'] ?? null
        );

        $this->aplicarStock(
            $query,
            $filtros['stock'] ?? null
        );

        $this->aplicarOrden(
            $query,
            $filtros['orden'] ?? 'name',
            $filtros['direccion'] ?? 'asc'
        );

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | Paginación
    |--------------------------------------------------------------------------
    */

    public function paginar(
        array $filtros = [],
        int $perPage = 10
    ): LengthAwarePaginator {

        return $this->listar($filtros)
            ->paginate($perPage)
            ->withQueryString();
    }

    /*
    |--------------------------------------------------------------------------
    | Búsquedas
    |--------------------------------------------------------------------------
    */

    public function buscar(string $texto): Collection
    {
        return $this->consultaBase()

            ->where(function (Builder $query) use ($texto) {

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
    | Estado
    |--------------------------------------------------------------------------
    */

    public function cambiarEstado(Product $product): Product
    {
        $product->update([
            'status' => ! $product->status,
        ]);

        return $product->fresh();
    }

    public function activar(Product $product): Product
    {
        $product->update(['status' => 1]);
        return $product->fresh();
    }

    public function desactivar(Product $product): Product
    {
        $product->update(['status' => 0]);
        return $product->fresh();
    }

    /*
    |--------------------------------------------------------------------------
    | Estadísticas
    |--------------------------------------------------------------------------
    */

    /**
     * Total de productos.
     */
    public function totalProductos(): int
    {
        return Product::count();
    }

    /**
     * Total de productos activos.
     */
    public function totalActivos(): int
    {
        return Product::where('status', true)->count();
    }

    /**
     * Productos con stock bajo.
     */
    public function productosStockBajo(int $limite = 10): Collection
    {
        return Product::with([
                'category',
                'brand'
            ])
            ->stockBajo()
            ->orderBy('stock')
            ->take($limite)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Consulta Base
    |--------------------------------------------------------------------------
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

        // limpiar strings
        $datos = array_map(function ($valor) {
            return is_string($valor) ? trim($valor) : $valor;
        }, $datos);

        // normalizar stock
        if (isset($datos['stock'])) {
            $datos['stock'] = (int) $datos['stock'];
        }

        if (isset($datos['stock_minimo'])) {
            $datos['stock_minimo'] = (int) $datos['stock_minimo'];
        }

        // normalizar precios
        foreach (['cost_price', 'sale_price'] as $field) {
            if (isset($datos[$field])) {
                $datos[$field] = (float) str_replace(',', '.', $datos[$field]);
            }
        }

        // normalizar status
        if (isset($datos['status'])) {
            $datos['status'] = filter_var($datos['status'], FILTER_VALIDATE_BOOLEAN);
        }

        $datos = $this->prepararSlug($datos, $product);
        $datos = $this->prepararSku($datos);

        return $datos;
    }

    /*
    |--------------------------------------------------------------------------
    | Aplicar búsqueda
    |--------------------------------------------------------------------------
    */

    private function aplicarBusqueda(
        Builder $query,
        ?string $buscar
    ): void {

        if (blank($buscar)) {
            return;
        }

        $query->where(function (Builder $consulta) use ($buscar) {

            $consulta

                ->where('name', 'like', "%{$buscar}%")

                ->orWhere('description', 'like', "%{$buscar}%")

                ->orWhere('sku', 'like', "%{$buscar}%")

                ->orWhere('barcode', 'like', "%{$buscar}%")

                ->orWhereHas('category', function (Builder $query) use ($buscar) {

                    $query->where('name', 'like', "%{$buscar}%");

                })

                ->orWhereHas('brand', function (Builder $query) use ($buscar) {

                    $query->where('name', 'like', "%{$buscar}%");

                })

                ->orWhereHas('tipoConsumo', function (Builder $query) use ($buscar) {

                    $query->where('nombre', 'like', "%{$buscar}%");

                });

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Aplicar categoría
    |--------------------------------------------------------------------------
    */

    private function aplicarCategoria(
        Builder $query,
        ?int $categoria
    ): void {

        if (blank($categoria)) {
            return;
        }

        $query->where('categories_id', $categoria);
    }

    /*
    |--------------------------------------------------------------------------
    | Aplicar marca
    |--------------------------------------------------------------------------
    */

    private function aplicarMarca(
        Builder $query,
        ?int $marca
    ): void {

        if (blank($marca)) {
            return;
        }

        $query->where('brand_id', $marca);
    }

    /*
    |--------------------------------------------------------------------------
    | Aplicar tipo de consumo
    |--------------------------------------------------------------------------
    */

    private function aplicarTipoConsumo(
        Builder $query,
        ?int $tipo
    ): void {

        if (blank($tipo)) {
            return;
        }

        $query->where('tipo_consumo_id', $tipo);
    }

    /*
    |--------------------------------------------------------------------------
    | Aplicar estado
    |--------------------------------------------------------------------------
    */

    private function aplicarEstado(
        Builder $query,
        $estado
    ): void {

        if ($estado === null || $estado === '') {
            return;
        }

        $query->where('status', (bool) $estado);
    }

    /*
    |--------------------------------------------------------------------------
    | Aplicar filtro stock
    |--------------------------------------------------------------------------
    */

    private function aplicarStock(
        Builder $query,
        ?string $stock
    ): void {

        if (blank($stock)) {
            return;
        }

        match ($stock) {

            'disponible' => $query->where('stock', '>', 0),

            'agotado' => $query->where('stock', 0),

            'stock_bajo' => $query->whereColumn(
                'stock',
                '<=',
                'stock_minimo'
            ),

            default => null,
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Aplicar orden
    |--------------------------------------------------------------------------
    */

    private function aplicarOrden(
        Builder $query,
        string $campo,
        string $direccion
    ): void {

        $camposPermitidos = [

            'name',

            'sale_price',

            'cost_price',

            'stock',

            'created_at',

            'updated_at',

        ];

        if (! in_array($campo, $camposPermitidos, true)) {

            $campo = 'name';

        }

        $direccion = strtolower($direccion) === 'desc'
            ? 'desc'
            : 'asc';

        $query->orderBy($campo, $direccion);
    }

    /*
    |--------------------------------------------------------------------------
    | Preparar slug
    |--------------------------------------------------------------------------
    */

    private function prepararSlug(
        array $datos,
        ?Product $product = null
    ): array {

        if (! empty($datos['slug'])) {

            $datos['slug'] = Str::slug($datos['slug']);

            return $datos;
        }

        if (! empty($datos['name'])) {

            $slug = Str::slug($datos['name']);

            if (
                $product === null ||
                $product->slug !== $slug
            ) {
                $datos['slug'] = $slug;
            }
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