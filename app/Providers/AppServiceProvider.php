<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\Wishlist;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Paginación compatible con Bootstrap 5
        Paginator::useBootstrapFive();

        // Compartir contador de wishlist con el header (si el usuario está logueado)
        View::composer('partials.header', function ($view) {
            $wishlistCount = auth()->check()
                ? Wishlist::where('user_id', auth()->id())->count()
                : 0;

            $view->with('wishlistCount', $wishlistCount);
        });
    }
}