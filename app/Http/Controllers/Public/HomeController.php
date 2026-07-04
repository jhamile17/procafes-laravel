<?php

namespace App\Http\Controllers\Public;
use App\Http\Controllers\Controller;
use App\Services\Catalogo\ProductService;
use App\Services\Catalogo\CategoryService;

class HomeController extends Controller
{
    public function __construct(
    protected ProductService $productService,
    protected CategoryService $categoryService,
){
}
    public function index()
    {
        return view('home', [
            'products' => $this->productService->obtenerDestacados(8),
            'categories' => $this->categoryService->obtenerActivas(),
        ]);
    }
}