<?php

namespace App\Providers;

use App\Services\MistServer\MistServerService;
use App\Services\SearchService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Cashier\Cashier;
use Stripe\Stripe;

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

      $this->app->singleton(SearchService::class, function ($app) {
        return new SearchService();
        // Add any dependencies if your SearchService constructor requires them
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

      Validator::extend('custom_streaming_url', function ($attribute, $value, $parameters, $validator) {
        return Str::startsWith($value, ['http://', 'https://', 'rtmp://', 'rtmps://', 'srt://', 'rtsp://', 'dtsc://']);
      }, 'The :attribute must be a valid streaming URL.');
    }
}
