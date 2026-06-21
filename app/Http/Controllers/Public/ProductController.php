<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand'])
            ->where('status', 1)
            ->latest();

        if ($request->filled('categoria')) {

            $categoria = $request->categoria;

            $query->whereHas('category', function ($q) use ($categoria) {

                $q->where('name', $categoria)
                  ->orWhere('categories_id', $categoria);

            });
        }

        $products = $query
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        $counts = DB::table('products')
            ->select(
                'categories_id',
                DB::raw('COUNT(*) as total')
            )
            ->where('status', 1)
            ->groupBy('categories_id')
            ->pluck('total', 'categories_id')
            ->toArray();

        return view(
            'products',
            compact(
                'products',
                'categories',
                'counts'
            )
        );
    }
}

