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
    | LISTADO
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

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        $brands = Brand::query()
            ->orderBy('name')
            ->get();

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
    | FORMULARIO CREAR
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
    | GUARDAR PRODUCTO
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreProductRequest $request
    ): RedirectResponse {

        try {

            /*
            |--------------------------------------------------------------------------
            | Datos validados
            |--------------------------------------------------------------------------
            */

            $datos = $request->validated();


            /*
            |--------------------------------------------------------------------------
            | IMAGEN
            |--------------------------------------------------------------------------
            |
            | Se guarda en:
            |
            | storage/app/public/products
            |
            | Y en BD:
            |
            | products/nombre-generado.png
            |
            */

            if ($request->hasFile('image')) {

                $datos['image'] = $request
                    ->file('image')
                    ->store(
                        'products',
                        'public'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | CREAR PRODUCTO
            |--------------------------------------------------------------------------
            |
            | ProductService se encarga de:
            |
            | - generar SKU
            | - generar slug
            | - colocar cost_price = 0
            | - determinar status según stock
            |
            */

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
    | FORMULARIO EDITAR
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
    | ACTUALIZAR PRODUCTO
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdateProductRequest $request,
        Product $product
    ): RedirectResponse {

        try {

            $datos = $request->validated();


            /*
            |--------------------------------------------------------------------------
            | NUEVA IMAGEN
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('image')) {

                /*
                | Eliminar imagen anterior
                */

                if (
                    !empty($product->image) &&
                    Storage::disk('public')->exists(
                        $product->image
                    )
                ) {

                    Storage::disk('public')->delete(
                        $product->image
                    );
                }


                /*
                | Guardar nueva imagen
                */

                $datos['image'] = $request
                    ->file('image')
                    ->store(
                        'products',
                        'public'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | ACTUALIZAR
            |--------------------------------------------------------------------------
            */

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
    | ACTIVAR / DESACTIVAR
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(
        Product $product
    ): RedirectResponse {

        try {

            /*
            |--------------------------------------------------------------------------
            | SI ESTÁ ACTIVO → DESACTIVAR
            |--------------------------------------------------------------------------
            */

            if ($product->status) {

                $this->productService->desactivar(
                    $product
                );

                return redirect()
                    ->route('admin.products.index')
                    ->with(
                        'success',
                        'Producto desactivado correctamente.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | SI ESTÁ AGOTADO → NO ACTIVAR
            |--------------------------------------------------------------------------
            */

            if ($product->stock <= 0) {

                return redirect()
                    ->route('admin.products.index')
                    ->with(
                        'error',
                        'No puedes activar este producto porque está agotado. Primero repón el stock.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | SI TIENE STOCK → ACTIVAR
            |--------------------------------------------------------------------------
            */

            $this->productService->activar(
                $product
            );


            return redirect()
                ->route('admin.products.index')
                ->with(
                    'success',
                    'Producto activado correctamente.'
                );


        } catch (\Throwable $e) {

            report($e);


            return redirect()
                ->route('admin.products.index')
                ->with(
                    'error',
                    'No fue posible cambiar el estado del producto.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ELIMINAR
    |--------------------------------------------------------------------------
    |
    | Se mantiene solamente como método técnico porque
    | posiblemente tu Route::resource() todavía lo registra.
    |
    | Pero ya NO deberías mostrar el botón eliminar
    | en la vista de productos.
    |
    */

    public function destroy(
        int $id
    ): RedirectResponse {

        try {

            $product = $this->productService->obtener($id);


            /*
            |--------------------------------------------------------------------------
            | ELIMINAR IMAGEN
            |--------------------------------------------------------------------------
            */

            if (
                !empty($product->image) &&
                Storage::disk('public')->exists(
                    $product->image
                )
            ) {

                Storage::disk('public')->delete(
                    $product->image
                );
            }


            /*
            |--------------------------------------------------------------------------
            | ELIMINAR PRODUCTO
            |--------------------------------------------------------------------------
            */

            $this->productService->eliminar(
                $product
            );


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