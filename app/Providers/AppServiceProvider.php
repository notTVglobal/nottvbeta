<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
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
        //        This is disabled, because we don't store
        //          a customer's billing information when
        //          they set up a new subscription.
        //          if we enable it we need to store
        //          the billing info before they submit the payment.
//                Cashier::calculateTaxes();
    }
}
