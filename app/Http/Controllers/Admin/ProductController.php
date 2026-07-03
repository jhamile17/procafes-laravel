<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\Catalogo\StoreProductRequest;
use App\Http\Requests\Catalogo\UpdateProductRequest;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\TipoConsumo;

use App\Services\Catalogo\ProductService;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Listado
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $products = $this->productService->paginar(
            filtros: $request->only([
                'buscar',
                'categoria',
                'marca',
                'tipo',
                'estado',
                'stock',
                'orden',
                'direccion',
            ]),
            perPage: 10
        );

        $categories = Category::orderBy('name')->get();

        $brands = Brand::orderBy('name')->get();

        return view(
            'admin.products.index',
            compact(
                'products',
                'categories',
                'brands'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Formulario Crear
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {
        $categories = Category::query()
            ->orderBy('name')
            ->get();

        $brands = Brand::query()
            ->orderBy('name')
            ->get();

        $tiposConsumo = TipoConsumo::query()
            ->orderBy('nombre')
            ->get();

        return view(
            'admin.products.create',
            compact(
                'categories',
                'brands',
                'tiposConsumo'
            )
        );
    }

        /*
    |--------------------------------------------------------------------------
    | Guardar producto
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreProductRequest $request
    ): RedirectResponse {

        try {

            $datos = $request->validated();

            if ($request->hasFile('image')) {

                $datos['image'] = $request
                    ->file('image')
                    ->store(
                        'products',
                        'public'
                    );

            }

            $this->productService->crear($datos);

            return redirect()

                ->route('admin.products.index')

                ->with(
                    'success',
                    'Producto registrado correctamente.'
                );

        } catch (\Throwable $e) {

            report($e);

            return back()

                ->withInput()

                ->with(
                    'error',
                    'No fue posible registrar el producto.'
                );

        }

    }

        /*
    |--------------------------------------------------------------------------
    | Formulario Editar
    |--------------------------------------------------------------------------
    */

    public function edit(
        Product $product
    ): View {

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        $brands = Brand::query()
            ->orderBy('name')
            ->get();

        $tiposConsumo = TipoConsumo::query()
            ->orderBy('nombre')
            ->get();

        return view(
            'admin.products.edit',
            compact(
                'product',
                'categories',
                'brands',
                'tiposConsumo'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar producto
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdateProductRequest $request,
        Product $product
    ): RedirectResponse {

        try {

            $datos = $request->validated();

            if ($request->hasFile('image')) {

                if (
                    $product->image &&
                    Storage::disk('public')->exists($product->image)
                ) {

                    Storage::disk('public')
                        ->delete($product->image);

                }

                $datos['image'] = $request
                    ->file('image')
                    ->store(
                        'products',
                        'public'
                    );

            }

            $this->productService->actualizar(
                $product,
                $datos
            );

            return redirect()

                ->route('admin.products.index')

                ->with(
                    'success',
                    'Producto actualizado correctamente.'
                );

        } catch (\Throwable $e) {

            report($e);

            return back()

                ->withInput()

                ->with(
                    'error',
                    'No fue posible actualizar el producto.'
                );

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar producto
    |--------------------------------------------------------------------------
    */

    public function destroy(
        int $id
    ): RedirectResponse {

        try {

            $product = $this->productService->obtener($id);

            if (
                $product->image &&
                Storage::disk('public')->exists($product->image)
            ) {

                Storage::disk('public')
                    ->delete($product->image);

            }

            $this->productService->eliminar($product);

            return redirect()

                ->route('admin.products.index')

                ->with(
                    'success',
                    'Producto eliminado correctamente.'
                );

        } catch (\Throwable $e) {

            report($e);

            return redirect()

                ->route('admin.products.index')

                ->with(
                    'error',
                    'No se pudo eliminar el producto.'
                );

        }

    }

}