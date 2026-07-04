<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Ventas\WishlistService;
use Illuminate\Http\Request;
use RuntimeException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
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

    public function index(Request $request):View|RedirectResponse
    {
        if (! $request->user()) {
            return redirect()
                ->route('login')
                ->with('info', 'Inicia sesión para ver tus favoritos.');
        }

        $items = $this->wishlistService
            ->obtenerFavoritos($request->user()->id);

        return view('wishlist.index', compact('items'));
    }

    /*
    |--------------------------------------------------------------------------
    | Agregar
    |--------------------------------------------------------------------------
    */

    public function store(
        Product $product,
        Request $request
    ):RedirectResponse{

        if (! $request->user()) {

            return redirect()
                ->route('login')
                ->with('info', 'Inicia sesión para agregar favoritos.');

        }

        try {

            $this->wishlistService->agregar(
                $request->user()->id,
                $product->id
            );

            return back()->with(
                'success',
                'Producto agregado a favoritos.'
            );

        } catch (RuntimeException $e) {

            return back()->with(
                'info',
                $e->getMessage()
            );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Product $product,
        Request $request
    ): RedirectResponse {

        if (! $request->user()) {

        return redirect()
            ->route('login')
            ->with('info', 'Inicia sesión para administrar tus favoritos.');

    }

    try {

        $this->wishlistService->eliminar(
            $request->user()->id,
            $product->id
        );

        return back()->with(
            'success',
            'Producto eliminado de favoritos.'
        );

    } catch (RuntimeException $e) {

        return back()->with(
            'info',
            $e->getMessage()
        );

    }
}

    /*
    |--------------------------------------------------------------------------
    | Toggle Ajax
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

        if ($this->wishlistService->existe(
            $userId,
            $productId
        )) {

            $this->wishlistService->eliminar(
                $userId,
                $productId
            );

            $added = false;

        } else {

            $this->wishlistService->agregar(
                $userId,
                $productId
            );

            $added = true;
        }
        return response()->json([
            'ok' => true,
            'added' => $added,
            'count' => $this->wishlistService
                ->contarFavoritos($userId),
        ]);
    }
}