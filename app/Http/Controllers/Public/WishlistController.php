<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // LISTA DE FAVORITOS
    public function index(Request $request)
    {
        $items = Wishlist::with('product')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('wishlist.index', compact('items'));
    }

    // AGREGAR
    public function store(Product $product, Request $request)
    {
        $userId = $request->user()->id;

        $exists = Wishlist::where([
            'user_id' => $userId,
            'product_id' => $product->id
        ])->exists();

        if ($exists) {
            return back()->with('info', 'Este producto ya está en tu lista de deseos.');
        }

        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Producto agregado a tu lista de deseos.');
    }

    // ELIMINAR
    public function destroy(Product $product, Request $request)
    {
        Wishlist::where([
            'user_id' => $request->user()->id,
            'product_id' => $product->id
        ])->delete();

        return back()->with('success', 'Producto eliminado de favoritos.');
    }

    // TOGGLE AJAX
    public function toggle(Request $request)
    {
        if (!$request->user()) {

            return response()->json([
                'ok' => false,
                'message' => 'Debe iniciar sesión.'
            ], 401);
        }

        $request->validate([
            'product_id' => [
                'required',
                'integer',
                'exists:products,id'
            ],
        ]);

        $userId = $request->user()->id;
        $productId = $request->product_id;

        $wishlist = Wishlist::where([
            'user_id' => $userId,
            'product_id' => $productId
        ])->first();

        if ($wishlist) {

            $wishlist->delete();

            $added = false;

        } else {

            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);

            $added = true;
        }

        return response()->json([
            'ok' => true,
            'added' => $added,
            'count' => Wishlist::where('user_id', $userId)->count()
        ]);
    }
}