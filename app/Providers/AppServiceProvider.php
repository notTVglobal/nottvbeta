<?php

namespace App\Providers;

use App\Services\MistServerService;
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
      $this->app->singleton(MistServerService::class, function ($app) {
        return new MistServerService();
      });
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
                Cashier::calculateTaxes();
    }
}
