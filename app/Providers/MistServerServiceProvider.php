<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\MistServer\MistServerService;

class MistServerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
      // Registering a singleton if the same instance should be used throughout the application
      $this->app->singleton(MistServerService::class, function ($app) {
        // Assuming MistServerService has dependencies, Laravel will resolve them automatically
        return new MistServerService();
      });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
