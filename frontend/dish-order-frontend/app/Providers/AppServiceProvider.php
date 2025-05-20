<?php

namespace App\Providers;

use App\Events\OrderStatusUpdate;
use App\Http\Controllers\Customer\CustomerOrderController;
use Illuminate\Support\Facades\Event;
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
    public function boot(): void
    {
        Event::listen(function (OrderStatusUpdate $event) {
            if($event->status)
                app()->make('App\Http\Controllers\Customer\CustomerOrderController')->payByOrderId($event->order_id);
        });
    }
}
