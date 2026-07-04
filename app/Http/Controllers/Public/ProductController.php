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
        $filters = [

            'buscar' => $request->input('search'),

            'categoria' => $request->input('categoria'),

        ];

        return view('products', [

            'products' => $this->productService->paginar($filters, 12),

            'categories' => $this->categoryService->obtenerActivas(),

            'counts' => $this->productService->contarPorCategorias(),

        ]);
    }
}