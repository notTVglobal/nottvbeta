<?php

namespace App\Listeners;

use App\Events\CreatorRegistrationCompleted;
use App\Mail\SendCreatorWelcomeEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendCreatorWelcomeEmailListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\CreatorRegistrationCompleted  $event
     * @return void
     */
    public function handle(CreatorRegistrationCompleted $event)
    {
      Mail::to($event->user->email)->send(new SendCreatorWelcomeEmail($event->user));
    }
}
