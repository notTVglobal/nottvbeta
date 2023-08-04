<?php

namespace App\Listeners;

use App\Events\NewVideoUploaded;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessNewUploadedVideo implements ShouldQueue
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
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'listeners';

    /**
     * The time (seconds) before the job should be processed.
     *
     * @var int
     */
    public int $delay = 0;

    /**
     * Handle the event.
     *
     * @param NewVideoUploaded $event
     * @return void
     */
    public function handle(NewVideoUploaded $event): void {
        // trigger email to user
        // trigger email to admin
    }
}
