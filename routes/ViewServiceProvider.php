<?php

namespace App\Providers;

use App\Models\Brand;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Sử dụng View Composer để chia sẻ dữ liệu với một view cụ thể (hoặc nhiều view)
        // Ở đây, chúng ta chia sẻ biến $brands với view 'layout'
        View::composer('layout', function ($view) {
            // Lấy tất cả các hãng và truyền vào view
            $view->with('brands', Brand::orderBy('name')->get());
        });
    }
}