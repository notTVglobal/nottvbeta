<?php

namespace App\Providers;

use App\Events\NewChatMessage;
use App\Events\NewVideoUploaded;
use App\Events\VideoProcessed;
use App\Events\ViewerCountIncrement;
use App\Listeners\LogRegisteredUser;
use App\Listeners\LogVerifiedUser;
use App\Listeners\ProcessNewUploadedVideo;
use App\Listeners\SendChatMessageNotification;
use App\Listeners\SendEmailVerification;
use App\Listeners\SendVideoProcessedNotification;
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
        NewVideoUploaded::class => [
          ProcessNewUploadedVideo::class,
        ],
        VideoProcessed::class => [
            SendVideoProcessedNotification::class,
        ],
        ViewerCountIncrement::class => [
            // add a listener, if needed.
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
