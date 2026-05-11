<?php

namespace App\Providers;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
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
        // Chia sẻ biến $cartCount với các view được chỉ định
        View::composer(['layout', 'cart'], function ($view) {
            $cartCount = Auth::check()
                ? CartItem::where('user_id', Auth::id())->count()
                : 0;
            $view->with('cartCount', $cartCount);
        });
    }
}