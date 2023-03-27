<?php

namespace App\Providers;

use App\Providers\VideoProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
     * @param  \App\Providers\VideoProcessed  $event
     * @return void
     */
    public function handle(VideoProcessed $event)
    {
        //
    }
}
