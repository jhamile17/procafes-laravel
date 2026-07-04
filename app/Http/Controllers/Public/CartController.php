<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\Ventas\CartService;
use App\Services\Ventas\SessionCartService;
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

    public function index(Request $request)
    {
        if ($request->user()) {

            $cart = $this->cartService->obtenerCarrito(
                $request->user()->id
            );

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

            'items' => $items,

            'count' => $count,

            'total' => $total,

        ]);
    }    
    
    /*
    |--------------------------------------------------------------------------
    | Agregar producto
    |--------------------------------------------------------------------------
    */

    public function add(Request $request)
    {
        $request->validate([

            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],

            'cantidad' => [
                'nullable',
                'integer',
                'min:1',
            ],

        ]);

        $product = Product::findOrFail(
            $request->product_id
        );

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

        return $this->index($request);
        
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar cantidad
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        int $productId
    ) {

        $request->validate([

            'cantidad' => [
                'required',
                'integer',
                'min:1',
            ],

        ]);

        if ($request->user()) {

            $cart = $this->cartService
                ->obtenerCarrito($request->user()->id);

            $item = $cart->items()
                ->where('product_id', $productId)
                ->firstOrFail();

            $this->cartService->actualizarCantidad(

                $item,

                (int) $request->cantidad

            );

        } else {

            $this->sessionCartService->actualizar(

                $request,

                $productId,

                (int) $request->cantidad

            );

        }

        return $this->index($request);
    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar
    |--------------------------------------------------------------------------
    */

    public function remove(
        Request $request,
        int $productId
    ) {

        if ($request->user()) {

            $cart = $this->cartService
                ->obtenerCarrito($request->user()->id);

            $item = $cart->items()
                ->where('product_id', $productId)
                ->first();

            if ($item) {

                $this->cartService
                    ->eliminarProducto($item);

            }

        } else {

            $this->sessionCartService
                ->eliminar($request, $productId);

        }

        return $this->index($request);
    }

    /*
    |--------------------------------------------------------------------------
    | Vaciar carrito
    |--------------------------------------------------------------------------
    */
    public function clear(Request $request)
    {
        if ($request->user()) {

            $cart = $this->cartService
                ->obtenerCarrito($request->user()->id);

            $this->cartService
                ->vaciarCarrito($cart);

        } else {

            $this->sessionCartService
                ->vaciar($request);

        }

        return $this->index($request);
    }
}
