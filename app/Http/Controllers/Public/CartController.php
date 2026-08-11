<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Models\Product;
use App\Services\Catalogo\RecommendationService;
use App\Services\Ventas\CartService;
use App\Services\Ventas\SessionCartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected SessionCartService $sessionCartService,
        protected RecommendationService $recommendationService,
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Vista del carrito
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('cart.index');
    }

    /*
    |--------------------------------------------------------------------------
    | Datos del carrito (AJAX)
    |--------------------------------------------------------------------------
    */

    public function data(Request $request): JsonResponse
    {
        return $this->response($request);
    }

    /*
    |--------------------------------------------------------------------------
    | Agregar producto
    |--------------------------------------------------------------------------
    */

    public function add(AddToCartRequest $request): JsonResponse
    {
        $product = Product::findOrFail($request->product_id);

        $cantidad = (int) ($request->cantidad ?? 1);

        if ($request->user()) {

            $this->cartService->agregarProducto(
                $request->user()->id,
                $product->id,
                $cantidad
            );

        } else {

            $this->sessionCartService->agregar(
                $request,
                $product,
                $cantidad
            );
        }

        return $this->response($request);
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar cantidad
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdateCartItemRequest $request,
        Product $product
    ): JsonResponse {

        if ($request->user()) {

            $cart = $this->cart($request);

            $item = $cart->items()
                ->where('product_id', $product->id)
                ->firstOrFail();

            $this->cartService->actualizarCantidad(
                $item,
                (int) $request->cantidad
            );

        } else {

            $this->sessionCartService->actualizar(
                $request,
                $product->id,
                (int) $request->cantidad
            );
        }

        return $this->response($request);
    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar producto
    |--------------------------------------------------------------------------
    */

    public function remove(
        Request $request,
        Product $product
    ): JsonResponse {

        if ($request->user()) {

            $cart = $this->cart($request);

            $item = $cart->items()
                ->where('product_id', $product->id)
                ->first();

            if ($item) {
                $this->cartService->eliminarProducto($item);
            }

        } else {

            $this->sessionCartService->eliminar(
                $request,
                $product->id
            );
        }

        return $this->response($request);
    }

    /*
    |--------------------------------------------------------------------------
    | Vaciar carrito
    |--------------------------------------------------------------------------
    */

    public function clear(Request $request): JsonResponse
    {
        if ($request->user()) {

            $this->cartService->vaciarCarrito(
                $this->cart($request)
            );

        } else {

            $this->sessionCartService->vaciar($request);
        }

        return $this->response($request);
    }

    /*
    |--------------------------------------------------------------------------
    | Productos recomendados
    |--------------------------------------------------------------------------
    */

    public function recommendations(Request $request)
    {
        if ($request->user()) {

            $items = $this->cartService->obtenerItems(
                $this->cart($request)
            );

        } else {

            $items = collect(
                $this->sessionCartService->obtener($request)
            );
        }

        $products = $this->recommendationService
            ->obtenerParaCarrito($items);

        return view(
            'components.cart.recommendations',
            compact('products')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Respuesta JSON
    |--------------------------------------------------------------------------
    */
    private function response(Request $request): JsonResponse
    {
        if ($request->user()) {

            $cart = $this->cart($request);

            $items = $this->cartService
                ->obtenerItems($cart)
                ->map(function ($item) {

                    return [
                        'product_id' => $item->product_id,
                        'name'       => $item->product->name,
                        'image'      => $item->product->image_url,
                        'unit_price' => (float) $item->unit_price,
                        'quantity'   => (int) $item->quantity,
                        'subtotal'   => (float) $item->subtotal,
                    ];
                })
                ->values();

            $count = $this->cartService->contarProductos($cart);

            $resumen = $this->cartService->calcularResumen($cart);

        } else {

            $items = collect(
                $this->sessionCartService->obtener($request)
            )
            ->map(function ($item) {

                return [
                    'product_id' => $item['product_id'],
                    'name'       => $item['name'],
                    'image'      => $item['image'],
                    'unit_price' => (float) $item['unit_price'],
                    'quantity'   => (int) $item['quantity'],
                    'subtotal'   => (float) ($item['subtotal'] ?? $item['sub_total']),
                ];
            })
            ->values();

            $count = $this->sessionCartService->cantidad($request);

            $resumen = $this->sessionCartService->calcularResumen($request);
        }

        return response()->json([
            'success'   => true,
            'items'     => $items,
            'count'     => $count,
            /* total para el carrito */
            'subtotal'  => $resumen['subtotal'],
            'igv'       => $resumen['igv'],
            /*el offcanvas mostrara el subtotal */
            'total'     => $resumen['total'],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener carrito
    |--------------------------------------------------------------------------
    */

    private function cart(Request $request)
    {
        return $this->cartService->obtenerCarrito(
            $request->user()->id
        );
    }
}