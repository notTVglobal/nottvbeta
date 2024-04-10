<?php

namespace App\Listeners;

use App\Events\VideoProcessed;

class SendVideoProcessedNotification
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
     * @param  \App\Events\VideoProcessed  $event
     * @return void
     */
    public function handle(VideoProcessed $event)
    {
        //
    }
}
