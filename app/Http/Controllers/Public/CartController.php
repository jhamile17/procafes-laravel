<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Services\Ventas\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService,
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Mostrar carrito
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        if (! $request->user()) {

            return redirect()
                ->route('login')
                ->with(
                    'info',
                    'Inicia sesión para ver tu carrito.'
                );

        }

        $cart = $this->cartService->obtenerCarrito(
            $request->user()->id
        );

        return view('cart.index', [

            'cart' => $cart,

            'items' => $this->cartService
                ->obtenerItems($cart),

            'total' => $this->cartService
                ->calcularTotal($cart),

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

        if (! $request->user()) {

            return redirect()
                ->route('login')
                ->with(
                    'info',
                    'Inicia sesión para agregar productos.'
                );

        }

        $this->cartService->agregarProducto(

            $request->user()->id,

            (int) $request->product_id,

            (int) ($request->cantidad ?? 1)

        );

        return back()->with(
            'success',
            'Producto agregado al carrito.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar cantidad
    |--------------------------------------------------------------------------
    */

    public function update(
        CartItem $item,
        Request $request
    ) {

        $request->validate([

            'cantidad' => [
                'required',
                'integer',
                'min:1',
            ],

        ]);

        $this->cartService->actualizarCantidad(

            $item,

            (int) $request->cantidad

        );

        return back()->with(
            'success',
            'Cantidad actualizada.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar
    |--------------------------------------------------------------------------
    */

    public function remove(CartItem $item)
    {
        $this->cartService->eliminarProducto($item);

        return back()->with(
            'success',
            'Producto eliminado.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Vaciar carrito
    |--------------------------------------------------------------------------
    */

    public function clear(Request $request)
    {
        $cart = $this->cartService->obtenerCarrito(
            $request->user()->id
        );

        $this->cartService->vaciarCarrito($cart);

        return back()->with(
            'success',
            'Carrito vaciado.'
        );
    }
}