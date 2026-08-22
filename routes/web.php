<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
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
use App\Http\Controllers\Public\SitemapController;
// Auth
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\CompleteRegistrationController;
use App\Http\Controllers\Auth\ResendRegistrationController;
// Cliente
use App\Http\Controllers\Customer\BoletaController as CustomerBoletaController;
use App\Http\Controllers\Customer\PedidoController;
use App\Http\Controllers\Customer\PerfilController;
use App\Http\Controllers\Customer\AddressController;
use App\Http\Controllers\Customer\DocumentController;
// Admin
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\CategoryController as CategoryController;
use App\Http\Controllers\Admin\BrandController as BrandController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController as UserController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ReportController;
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
        
    Route::get('/recommendations', [CartController::class, 'recommendations'])
        ->name('cart.recommendations');

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
Route::get('/login-checkout', function () {
    session([
        'url.intended' => route('checkout.index')
    ]);
    return redirect()->route('login');
})->name('login.checkout');
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
// Checkout
Route::middleware('auth')->group(function () {

    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout.index');

    Route::post('/checkout', [CheckoutController::class, 'store'])
        ->name('checkout.store');

    Route::get('/checkout/success/{order}', function (Order $order) {
        return view('checkout.success', compact('order'));
    })->name('checkout.success');

});
/*
|--------------------------------------------------------------------------
| LocationIQ
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('customer/addresses')
    ->name('customer.addresses.')
    ->group(function () {


        /*
        |--------------------------------------------------------------------------
        | Buscar dirección
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/search',
            [AddressController::class, 'search']
        )->name('search');


        /*
        |--------------------------------------------------------------------------
        | Guardar dirección
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/',
            [AddressController::class, 'update']
        )->name('update');


    });

Route::middleware('auth')
    ->prefix('mercado-pago')
    ->name('mp.')
    ->group(function () {

        Route::get(
            '/success',
            [MercadoPagoController::class, 'success']
        )->name('success');

        Route::get(
            '/pending',
            [MercadoPagoController::class, 'pending']
        )->name('pending');

        Route::get(
            '/failure',
            [MercadoPagoController::class, 'failure']
        )->name('failure');

    });

/*
|--------------------------------------------------------------------------
| Mercado Pago - Webhook
|--------------------------------------------------------------------------
*/

Route::post('/mercado-pago/webhook', [MercadoPagoWebhookController::class, 'handle'])
    ->name('mp.webhook');



Route::middleware('auth')
    ->prefix('customer/documentos')
    ->name('customer.documentos.')
    ->group(function () {

        Route::post(
            '/dni',
            [DocumentController::class, 'dni']
        )->name('dni');

        Route::post(
            '/ruc',
            [DocumentController::class, 'ruc']
        )->name('ruc');

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

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

        Route::resource('/categories', CategoryController::class);
        Route::resource('/brands', BrandController::class);
        Route::resource('/products', AdminProductController::class);
        Route::resource('/users', UserController::class);

        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index');
        Route::prefix('reports')->name('reports.')->group(function () {

        Route::get('/sales', [ReportController::class, 'sales'])
            ->name('sales');

        Route::get('/best-sellers', [ReportController::class, 'bestSellers'])
            ->name('best-sellers');

        Route::get('/least-sellers', [ReportController::class, 'leastSellers'])
            ->name('least-sellers');

        Route::get('/inventory', [ReportController::class, 'inventory'])
            ->name('inventory');

        Route::get('/categories', [ReportController::class, 'categories'])
            ->name('categories');

        Route::get('/products', [ReportController::class, 'products'])
            ->name('products');

        Route::get('/orders', [ReportController::class, 'orders'])
            ->name('orders');
        });
        Route::get('/billing', [BillingController::class, 'index'])
            ->name('billing.index');
        Route::post('/billing/lookup', [BillingController::class, 'lookup'])
            ->name('billing.lookup');
        Route::patch(
                '/billing/{order}/approve-payment',
                [BillingController::class, 'approvePayment']
            )->name('billing.approve-payment');
        Route::get('/orders', [OrderController::class, 'index'])
            ->name('orders.index');
        
        Route::get('/orders/{order}', [OrderController::class, 'show'])
            ->name('orders.show');
        
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
            ->name('orders.status');
        Route::get('orders/{order}/download',[OrderController::class, 'download']
                )->name('orders.download');
        Route::get(
            '/configuracion-empresa',
            [ConfiguracionEmpresaController::class, 'index']
        )->name('configuracion.index');

        Route::put(
            '/configuracion-empresa',
            [ConfiguracionEmpresaController::class, 'update']
        )->name('configuracion.update');
    });
    Route::get('/sitemap.xml', [SitemapController::class, 'index'])
    ->name('sitemap');
    
/*
|--------------------------------------------------------------------------
| AUTH SYSTEM
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
