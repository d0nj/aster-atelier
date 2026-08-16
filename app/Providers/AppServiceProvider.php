<?php

namespace App\Providers;

use App\Orders\CartStore;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewInstance;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Every storefront page shows a cart badge count in the shared
        // layout. One seam supplies it so no controller hands it through.
        // Composed on the child views because Blade renders a child's
        // sections before the parent layout, and child data flows up into
        // the layout it extends.
        View::composer(['storefront.*', 'orders.*', 'auth.*'], function (ViewInstance $view): void {
            $view->with('cartCount', app(CartStore::class)->snapshot()->count());
        });
    }
}
