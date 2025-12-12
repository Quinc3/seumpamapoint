<?php

namespace App\Providers;

use register;
use Filament\Panel;
use App\Models\Order;
use App\Observers\OrderObserver;
use Illuminate\Support\ServiceProvider;

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
    public function boot()
    {
        // Order::observe(OrderObserver::class);
    }
}
