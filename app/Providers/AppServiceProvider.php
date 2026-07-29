<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Wishlist;
use App\Services\Sistema\ConfiguracionEmpresaService;
use App\Observers\ProductObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        /*
        |--------------------------------------------------------------------------
        | Header
        |--------------------------------------------------------------------------
        */

        View::composer('partials.header', function ($view) {

            $wishlistCount = auth()->check()
                ? Wishlist::where('user_id', auth()->id())->count()
                : 0;

            $view->with('wishlistCount', $wishlistCount);
        });

        /*
        |--------------------------------------------------------------------------
        | Configuración de la empresa
        |--------------------------------------------------------------------------
        */

        View::composer('*', function ($view) {

            try {

                $configuracion = app(ConfiguracionEmpresaService::class)->obtener();

            } catch (\Throwable $e) {

                $configuracion = null;

            }

            $view->with('configuracion', $configuracion);

        });

        /*
        |--------------------------------------------------------------------------
        | Observers
        |--------------------------------------------------------------------------
        */

        Product::observe(ProductObserver::class);
    }
}