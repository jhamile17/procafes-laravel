<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $products = Product::query()
            ->where('status', 1)
            ->where('stock', '>', 0)
            ->get();

        return response()
            ->view('sitemap', compact('products'))
            ->header('Content-Type', 'application/xml');
    }
}