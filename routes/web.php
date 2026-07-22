<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\CompleteRegistrationController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Notifications\UsuarioReactivacion;

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
use App\Http\Controllers\Auth\ResendRegistrationController;
use App\Http\Controllers\Auth\WelcomeController;
use App\Http\Controllers\Auth\CheckEmailController;
// Cliente
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\BoletaController as CustomerBoletaController;



// Admin
use App\Http\Controllers\Admin\CategoryController as CategoryController;
use App\Http\Controllers\Admin\BrandController as BrandController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController as UserController;
use App\Http\Controllers\Admin\DashboardController as DashboardController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\OrderController;

// Checkout / pagos
use App\Http\Controllers\PaymentDemoController;
// Mercado Pago
use App\Http\Controllers\Payment\MercadoPagoController;
use App\Http\Controllers\Payment\MercadoPagoWebhookController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])
    ->name('home');
Route::view('/nosotros', 'nosotros')
    ->name('nosotros');
Route::get('/ubicanos', [UbicanosController::class, 'index'])
    ->name('ubicanos');
//productos
Route::get('/products', [ProductController::class, 'index'])
    ->name('products');
/*
|--------------------------------------------------------------------------
CHATBOT
|--------------------------------------------------------------------------
*/
Route::post('/chatbot', [ChatbotController::class, 'chat']);

//wishlist favoritos
Route::prefix('wishlist')
    ->name('wishlist.')
    ->group(function () {

        Route::get('/', [WishlistController::class, 'index'])
            ->middleware('auth')
            ->name('index');

        Route::post('/toggle', [WishlistController::class, 'toggle'])
            ->name('toggle');

        Route::post('/sync', [WishlistController::class, 'sync'])
            ->middleware('auth')
            ->name('sync');

    });
/*
 CARRITO
*/
Route::prefix('cart')->group(function () {

    Route::get('/', [CartController::class, 'index'])
        ->name('cart.index');

    Route::post('/', [CartController::class, 'add'])
        ->name('cart.add');

    Route::patch('/{productId}', [CartController::class, 'update']);

    Route::delete('/{productId}', [CartController::class, 'remove']);

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
    Route::get(
        '/register/verify/{token}',
        CompleteRegistrationController::class
    )->name('register.complete');
    Route::get(
    '/register/check-email',
    CheckEmailController::class
    )->name('register.check-email');

  Route::get(
    '/register/welcome',
    WelcomeController::class
    )->middleware('auth')
    ->name('register.welcome');
    Route::post(
        '/register/resend',
        ResendRegistrationController::class
    )->name('register.resend');
/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

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

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/cliente', [CustomerDashboardController::class, 'index'])
        ->name('customer.dashboard');

    Route::post('/cliente/foto', [CustomerDashboardController::class, 'updatePhoto'])
        ->name('customer.photo.update');

    Route::get('/cliente/pedidos/{order}/boleta', [CustomerBoletaController::class, 'download'])
        ->name('customer.boleta.download');

    Route::view('/profile', 'profile')->name('profile');

    Route::view('/mis-productos', 'customer.products')
        ->name('customer.products');

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

        Route::get('/orders', [OrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/billing', [BillingController::class, 'index'])
            ->name('billing.index');
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
/*
Route::get('/pagos/mercadopago', [MercadoPagoController::class, 'index'])
    ->middleware('auth', 'verified')
    ->name('mp.checkout');
Route::post('/pagos/crear-preferencia', [MercadoPagoController::class, 'createPreference'])
    ->middleware('auth', 'verified')
    ->name('mp.preference');
Route::get('/pagos/exito', [MercadoPagoController::class, 'success'])
    ->name('mp.success');

Route::get('/pagos/pendiente', [MercadoPagoController::class, 'pending'])
    ->name('mp.pending');

Route::get('/pagos/error', [MercadoPagoController::class, 'failure'])
    ->name('mp.failure');

Route::post('/webhooks/mercadopago', [MercadoPagoWebhookController::class, 'handle'])
    ->name('mp.webhook');
*/
/*
|--------------------------------------------------------------------------
| AUTH SYSTEM
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';