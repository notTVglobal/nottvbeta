<?php

namespace App\Listeners;

use App\Events\ProcessVideoInfoCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ProcessVideoInfoListener
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
     * @param  \App\Events\ProcessVideoInfoCompleted  $event
     * @return void
     */
    public function handle(ProcessVideoInfoCompleted $event)
    {
        // Access event data
        $videoId = $event->videoId;

        // Access the message array from the event
        $message = $event->message;

        // Check the message type
        $messageType = $message['type']; // 'success', 'error', etc.
        $messageContent = $message['content']; // The content of the message

        // Handle the message based on its type
        if ($messageType === 'success') {
            // Handle success message
            // Example: Log or display a success message
//            Log::info('Success Message: ' . $messageContent);
            Log::channel('custom_error')->info('Success Message: ' . $messageContent);

        } elseif ($messageType === 'error') {
            // Handle error message
            // Example: Log or display an error message
//            Log::error('Error Message: ' . $messageContent);
            Log::channel('custom_error')->error('Error Message: ' . $messageContent);

        }

        // Handle the event logic here
        // For example, you can log the event or perform other actions
        Log::channel('custom_error')->info('Video ID ' . $videoId . ' processing completed.');


    }
}
