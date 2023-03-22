<?php

namespace App\Providers;

use App\Listeners\LogRegisteredUser;
use App\Listeners\LogVerifiedUser;
use App\Listeners\SendChatMessageNotification;
use App\Listeners\SendEmailVerification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use App\Events\NewChatMessage;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            // this first sendEmail is from Fortify
//            SendEmailVerificationNotification::class,
            //this second sendEmail is our custom listener
            SendEmailVerification::class,
            LogRegisteredUser::class,
        ],
        Verified::class => [
            LogVerifiedUser::class,
        ],
        NewChatMessage::class => [
            SendChatMessageNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
