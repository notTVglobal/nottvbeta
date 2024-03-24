<?php

namespace App\Providers;

use App\Events\CreatorRegistrationCompleted;
use App\Events\NewChatMessage;
use App\Events\NewNotificationEvent;
use App\Events\NewVideoUploaded;
use App\Events\ViewerPresenceChannel;
use App\Events\VideoProcessed;
use App\Listeners\LogRegisteredUser;
use App\Listeners\LogVerifiedUser;
use App\Listeners\ProcessNewVideoUpload;
use App\Listeners\SendChatMessageNotification;
use App\Listeners\SendCreatorWelcomeEmailListener;
use App\Listeners\SendEmailVerification;
use App\Listeners\SendNewUserNotification;
use App\Listeners\SendVideoProcessedNotification;
use App\Listeners\ViewerPresenceChannelListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
        NewNotificationEvent::class => [
            SendNewUserNotification::class,
        ],
        NewVideoUploaded::class => [
          ProcessNewVideoUpload::class,
        ],
        VideoProcessed::class => [
            SendVideoProcessedNotification::class,
        ],
        ViewerPresenceChannel::class => [
            ViewerPresenceChannelListener::class,
        ],
        CreatorRegistrationCompleted::class => [
            SendCreatorWelcomeEmailListener::class,
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
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
