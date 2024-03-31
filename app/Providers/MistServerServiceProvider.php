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

      // Register a singleton for the "push" server type
      $this->app->singleton('mistserver.push', function ($app) {
        return new MistServerService('push');
      });

      // Register a singleton for the "playback" server type
      $this->app->singleton('mistserver.playback', function ($app) {
        return new MistServerService('playback');
      });

      // Register a singleton for the "playback" server type
      $this->app->singleton('mistserver.vod', function ($app) {
        return new MistServerService('vod');
      });

      // Register a singleton for the "playback" server type
      $this->app->singleton('mistserver.recording', function ($app) {
        return new MistServerService('recording');
      });

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
