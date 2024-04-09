<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes();
//        Broadcast::channel('private-chat.1', function ($user, $userId) {
//            return $user->id === $userId;
//        });

        require base_path('routes/channels.php');
    }
}
