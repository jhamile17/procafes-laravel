<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Ventas\SessionWishlistService;
use App\Services\Ventas\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function __construct(
        protected WishlistService $wishlistService,
        protected SessionWishlistService $sessionWishlistService,
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener favoritos
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View|JsonResponse
    {
        if ($request->user()) {

            $products = $this->wishlistService
                ->obtenerFavoritos($request->user()->id);

            $count = $this->wishlistService
                ->contarFavoritos($request->user()->id);

        } else {

            $productIds = $this->sessionWishlistService
                ->obtener($request);

            $collection = Product::query()
                ->with([
                    'category',
                    'brand',
                ])
                ->whereIn('id', $productIds)
                ->get();

            $products = $this->wishlistService
                ->formatearProductos($collection);

            $count = $this->sessionWishlistService
                ->contar($request);

        }

        /*
        |--------------------------------------------------------------------------
        | Vista
        |--------------------------------------------------------------------------
        */

        if (! $request->expectsJson()) {

            return view('customer.wishlist.index', [

                'user' => $request->user(),
                'products' => $products,
                'count' => $count,

            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | API
        |--------------------------------------------------------------------------
        */

        $favorites = collect($products)
            ->pluck('product_id')
            ->values();

        return response()->json([

            'items' => $products,

            'favorites' => $favorites,

            'count' => $count,

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Agregar / Eliminar favorito
    |--------------------------------------------------------------------------
    */

    public function toggle(Request $request): JsonResponse
    {
        $request->validate([

            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],

        ]);

        $productId = (int) $request->product_id;

        if ($request->user()) {

            $added = $this->wishlistService->toggle(

                $request->user()->id,

                $productId

            );

            $count = $this->wishlistService
                ->contarFavoritos($request->user()->id);

        } else {

            $added = $this->sessionWishlistService->toggle(

                $request,

                $productId

            );

            $count = $this->sessionWishlistService
                ->contar($request);

        }

        return response()->json([

            'ok' => true,

            'added' => $added,

            'count' => $count,

            'message' => $added
                ? 'Producto agregado a favoritos.'
                : 'Producto eliminado de favoritos.',

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Contador
    |--------------------------------------------------------------------------
    */

    public function count(Request $request): JsonResponse
    {
        $count = $request->user()

            ? $this->wishlistService
                ->contarFavoritos($request->user()->id)

            : $this->sessionWishlistService
                ->contar($request);

        return response()->json([

            'count' => $count,

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Vaciar favoritos
    |--------------------------------------------------------------------------
    */

    public function clear(Request $request): JsonResponse
    {
        if ($request->user()) {

            $this->wishlistService
                ->vaciarFavoritos($request->user()->id);

        } else {

            $this->sessionWishlistService
                ->vaciar($request);

        }

        return response()->json([

            'ok' => true,

            'count' => 0,

            'items' => [],

            'favorites' => [],

            'message' => 'Lista de favoritos vaciada correctamente.',

        ]);
    }
}