<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Mostrar página principal.
     */
    public function index()
    {
        $products = Product::with(['category', 'brand'])
            ->where('status', 1)
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact('products'));
    }
}