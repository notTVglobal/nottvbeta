<?php

namespace App\Providers;

use App\Providers\VideoUploaded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcessVideo implements ShouldQueue
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
     * @param  \App\Providers\VideoUploaded  $event
     * @return void
     */
    public function handle(VideoUploaded $event)
    {
        //
    }
}
