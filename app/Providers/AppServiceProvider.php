<?php

namespace App\Providers;

use App\Models\Wishlist;
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

        View::composer('partials.header', function ($view) {
            $wishlistCount = auth()->check()
                ? Wishlist::where('user_id', auth()->id())->count()
                : 0;

            $view->with('wishlistCount', $wishlistCount);
        });
    }
}