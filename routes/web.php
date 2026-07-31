<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Notifications\UsuarioReactivacion;

use App\Livewire\Pages\Auth\CheckEmail;
// Público
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ProductController;
use App\Http\Controllers\Public\CartController;
use App\Http\Controllers\Public\WishlistController;
use App\Http\Controllers\Public\CheckoutController;
use App\Http\Controllers\Public\ChatbotController;
use App\Http\Controllers\Public\UbicanosController;

// Auth
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\CompleteRegistrationController;
use App\Http\Controllers\Auth\ResendRegistrationController;
// Cliente
use App\Http\Controllers\Customer\BoletaController as CustomerBoletaController;
use App\Http\Controllers\Customer\PedidoController;
use App\Http\Controllers\Customer\PerfilController;
use App\Http\Controllers\Customer\AddressController;
// Admin
use App\Http\Controllers\Admin\CategoryController as CategoryController;
use App\Http\Controllers\Admin\BrandController as BrandController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController as UserController;
use App\Http\Controllers\Admin\DashboardController as DashboardController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ConfiguracionEmpresaController;

// Checkout / pagos
use App\Http\Controllers\PaymentDemoController;
// Mercado Pago
use App\Http\Controllers\Payment\MercadoPagoController;
use App\Http\Controllers\Payment\MercadoPagoWebhookController;

/*RUTAS PÚBLICAS*/
Route::get('/', [HomeController::class, 'index'])
    ->name('home');
Route::view('/nosotros', 'nosotros')
    ->name('nosotros');
Route::get('/ubicanos', [UbicanosController::class, 'index'])
    ->name('ubicanos');
//productos
Route::get('/products', [ProductController::class, 'index'])
    ->name('products');
/*CHATBOT*/
Route::post('/chatbot', [ChatbotController::class, 'chat']);
//wishlist favoritos
Route::prefix('wishlist')
    ->name('wishlist.')
    ->group(function () {
        Route::get(
            '/',
            [WishlistController::class, 'index']
        )->name('index');
        Route::post('/toggle', [WishlistController::class, 'toggle'])
            ->name('toggle');
        Route::get(
            '/count',
            [WishlistController::class, 'count']
        )->name('count');
        Route::delete(
            '/',
            [WishlistController::class, 'clear']
        )->name('clear');

    });
/*
|--------------------------------------------------------------------------
| CARRITO
|--------------------------------------------------------------------------
*/

Route::prefix('cart')->group(function () {

    Route::get('/', [CartController::class, 'index'])
        ->name('cart.index');

    Route::get('/data', [CartController::class, 'data'])
        ->name('cart.data');

    Route::post('/', [CartController::class, 'add'])
        ->name('cart.add');

    Route::patch('/{product}', [CartController::class, 'update'])
        ->name('cart.update');

    Route::delete('/{product}', [CartController::class, 'remove'])
        ->name('cart.remove');

    Route::delete('/', [CartController::class, 'clear'])
        ->name('cart.clear');

});
/*
|--------------------------------------------------------------------------
| GOOGLE AUTH
|--------------------------------------------------------------------------
*/

Route::prefix('auth/google')->name('auth.google.')->group(function () {

    Route::get('/login', [GoogleController::class, 'redirectLogin'])
        ->name('login');

    Route::get('/register', [GoogleController::class, 'redirectRegister'])
        ->name('register');

    Route::get('/callback', [GoogleController::class, 'callback'])
        ->name('callback');

    });
/*Registro de usuarios */
    Route::get(
        '/register/verify/{token}',
        CompleteRegistrationController::class
    )->name('register.complete');

    Route::get(
    '/register/check-email',CheckEmail::class
    )->name('register.check-email');

    Route::post(
        '/register/resend',
        ResendRegistrationController::class
    )->name('register.resend');
/*LOGOUT*/

Route::post('/logout', function (Request $request) {

    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');

})->middleware('auth')->name('logout');



Route::get('/reactivar-test', function () {

    $usuarios = User::whereNotNull('email')
        ->where('email', 'like', '%@%') // básico
        ->get();

    foreach ($usuarios as $user) {

        // Validar email correctamente
        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            continue; // saltar correos inválidos
        }

        $productos = Product::inRandomOrder()->take(3)->get();

        $user->notify(new UsuarioReactivacion($productos));
    }

    return "Correos enviados";
});

/*
|--------------------------------------------------------------------------
| CLIENTE (VERIFICADO)
|--------------------------------------------------------------------------
*/
    Route::prefix('cliente')
    ->middleware(['auth', 'verified'])
    ->name('customer.')
    ->group(function () {

        Route::get('/', [PerfilController::class, 'index'])
            ->name('profile');
        Route::put('/mi-perfil/foto', [PerfilController::class, 'updatePhoto'])
            ->name('profile.photo');
        Route::get('/mi-perfil/editar', [PerfilController::class, 'edit'])
            ->name('profile.edit');
        Route::put('/editar', [PerfilController::class, 'update'])
            ->name('profile.update');
        Route::get('/mi-perfil/configuracion',[PerfilController::class, 'settings']
            )->name('profile.settings');
        Route::put('/mi-perfil/contrasena', [PerfilController::class, 'updatePassword'])
            ->name('profile.password.update');
            
        Route::get('/pedidos', [PedidoController::class, 'index'])
            ->name('orders');
        Route::get('/pedidos/{order}', [PedidoController::class, 'show'])
            ->name('orders.show');
        /*Route::get('/direccion/busqueda',[AddressController::class, 'search'])->name('address.search');*/
        Route::get('/favoritos', [WishlistController::class, 'index'])
            ->name('wishlist');
    });

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified'])
    ->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

        Route::resource('/categories', CategoryController::class);
        Route::resource('/brands', BrandController::class);
        Route::resource('/products', AdminProductController::class);
        Route::resource('/users', UserController::class);

        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index');

        Route::get('/billing', [BillingController::class, 'index'])
            ->name('billing.index');

        /*
        |--------------------------------------------------------------------------
        | ÓRDENES
        |--------------------------------------------------------------------------
        */

        Route::prefix('orders')
            ->name('orders.')
            ->group(function () {

                Route::get('/', [OrderController::class, 'index'])
                    ->name('index');

                Route::get('/{order}', [OrderController::class, 'show'])
                    ->name('show');

                Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])
                    ->name('status');

            });

         Route::get(
            '/configuracion-empresa',
            [ConfiguracionEmpresaController::class, 'index']
        )->name('configuracion.index');

        Route::put(
            '/configuracion-empresa',
            [ConfiguracionEmpresaController::class, 'update']
        )->name('configuracion.update');
    });

/*
|--------------------------------------------------------------------------
| CHECKOUT (PUENTE PARA FRONTEND)
|--------------------------------------------------------------------------
*/
Route::get('/checkout', [CheckoutController::class, 'index'])
    ->middleware('auth', 'verified')
    ->name('checkout');

Route::post('/checkout', [CheckoutController::class, 'store'])
    ->middleware('auth', 'verified')
    ->name('checkout.store');

/*
|--------------------------------------------------------------------------
| MERCADO PAGO
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Retornos de Mercado Pago
    |--------------------------------------------------------------------------
    */

    Route::get('/pagos/exito', [
        MercadoPagoController::class,
        'success'
    ])->name('mp.success');

    Route::get('/pagos/pendiente', [
        MercadoPagoController::class,
        'pending'
    ])->name('mp.pending');

    Route::get('/pagos/error', [
        MercadoPagoController::class,
        'failure'
    ])->name('mp.failure');

});

/*
|--------------------------------------------------------------------------
| Webhook Mercado Pago
|--------------------------------------------------------------------------
|
| Esta ruta NO debe tener middleware auth.
| Mercado Pago la invoca directamente.
|
*/

Route::post('/webhooks/mercadopago', [
    MercadoPagoWebhookController::class,
    'handle'
])->name('mp.webhook');
/*
|--------------------------------------------------------------------------
| AUTH SYSTEM
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';