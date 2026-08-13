<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\Catalogo\ProductService;
use App\Services\Catalogo\CategoryService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected CategoryService $categoryService,
    ) {
    }

    public function index(Request $request)
    {
        $search = trim(
            (string) $request->input('search', '')
        );

        if ($search !== '') {

            $products = $this->productService
                ->buscarCliente($search, 12);

        } else {

            $products = $this->productService
                ->paginar([], 12)
                ->withQueryString();

        }

        return view('products', [

            'products' => $products,

            'categories' => $this->categoryService
                ->obtenerActivas(),

            'counts' => $this->productService
                ->contarPorCategorias(),

        ]);
    }
}