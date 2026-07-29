<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Models\Product;
use App\Models\Cart;
use App\Services\Ventas\CartService;
use App\Services\Ventas\SessionCartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected SessionCartService $sessionCartService,
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Mostrar carrito
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('cart.index');
    }
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

            $cart = $this->cart($request);

            $this->cartService->vaciarCarrito($cart);

        } else {

            $this->sessionCartService->vaciar($request);
        }

        return $this->response($request);
    }

    /*
    |--------------------------------------------------------------------------
    | Construir respuesta del carrito
    |--------------------------------------------------------------------------
    */

    private function response(Request $request): JsonResponse
    {
        if ($request->user()) {

            $cart = $this->cart($request);

            $items = $this->cartService->obtenerItems($cart);

            $count = $this->cartService->contarProductos($cart);

            $total = $this->cartService->calcularTotal($cart);

        } else {

            $items = collect(
                $this->sessionCartService->obtener($request)
            )->values();

            $count = $this->sessionCartService->cantidad($request);

            $total = $this->sessionCartService->total($request);
        }

        return response()->json([
            'success'=> true,
            'items' => $items,
            'count' => $count,
            'total' => $total,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener carrito del usuario autenticado
    |--------------------------------------------------------------------------
    */

    private function cart(Request $request)
    {
        return $this->cartService->obtenerCarrito(
            $request->user()->id
        );
    }
    
}