<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\Ventas\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function __construct(
        protected WishlistService $wishlistService,
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Lista de favoritos
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View|RedirectResponse
    {
        if (! $request->user()) {

            return redirect()
                ->route('login')
                ->with(
                    'info',
                    'Inicia sesión para ver tus favoritos.'
                );

        }

        $items = $this->wishlistService
            ->obtenerFavoritos(
                $request->user()->id
            );

        return view(
            'wishlist.index',
            compact('items')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Alternar favorito (AJAX)
    |--------------------------------------------------------------------------
    */

    public function toggle(Request $request): JsonResponse
    {
        if (! $request->user()) {

            return response()->json([
                'ok' => false,
                'message' => 'Debe iniciar sesión.',
            ], 401);

        }

        $request->validate([
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],
        ]);

        $userId = $request->user()->id;

        $productId = (int) $request->product_id;

        $added = $this->wishlistService->toggle(
            $userId,
            $productId
        );

        return response()->json([

            'ok' => true,

            'added' => $added,

            'count' => $this->wishlistService
                ->contarFavoritos($userId),

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Sincronizar favoritos del LocalStorage
    |--------------------------------------------------------------------------
    */

    public function sync(Request $request): JsonResponse
    {
        if (! $request->user()) {

            return response()->json([
                'ok' => false,
                'message' => 'Debe iniciar sesión.',
            ], 401);

        }

        $request->validate([
            'favorites' => [
                'required',
                'array',
            ],
            'favorites.*' => [
                'integer',
                'exists:products,id',
            ],
        ]);

        $this->wishlistService->sincronizar(
            $request->user()->id,
            $request->favorites
        );

        return response()->json([
            'ok' => true,
            'count' => $this->wishlistService
                ->contarFavoritos(
                    $request->user()->id
                ),
        ]);
    }
}