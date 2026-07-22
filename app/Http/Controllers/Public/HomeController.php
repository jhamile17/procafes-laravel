<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\Catalogo\ProductService;
use App\Services\Home\MethodService;

class HomeController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected MethodService $methodService,
    ) {
    }
        public function index()
        {
            return view('home', [
                'products'   => $this->productService->obtenerDestacados(8),
                'methods'    => $this->methodService->obtenerTodos(),
            ]);
        }
}