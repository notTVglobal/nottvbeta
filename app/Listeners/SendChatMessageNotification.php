<?php

namespace App\Listeners;

use App\Events\NewChatMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendChatMessageNotification
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
     * @param  \App\Events\NewChatMessage  $event
     * @return void
     */
    public function handle(NewChatMessage $event)
    {
        // Add events here, e.g., when
        // a new chat message happens
        // send a notification to the
        // admin or show owner.
    }
}
