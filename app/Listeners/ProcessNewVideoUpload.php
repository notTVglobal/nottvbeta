<?php

namespace App\Listeners;

use App\Events\NewVideoUploaded;
use App\Jobs\SendAdminNotification;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\AdminNotification;
use Illuminate\Support\Facades\Mail;

class ProcessNewVideoUpload implements ShouldQueue {
  /**
   * Create the event listener.
   *
   * @return void
   */
  public function __construct() {
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
    $video = $event->video;

    // Optionally, load related data if not already loaded
    // $video->load('user', 'showEpisode.show', 'movie', 'movieTrailer');

    // Fetch user name (assuming a relationship 'user' exists in Video model)
    $userName = $video->user ? $video->user->name : 'Unknown User';

    // Determine the context of the video (Show, Movie, or Movie Trailer)
    $context = '';
    if ($video->show_episodes_id) {
      $context = 'Show: ' . ($video->showEpisode->show->name ?? 'Unknown') .
          ', Episode: ' . ($video->showEpisode->name ?? 'Unknown');
    } elseif ($video->movies_id) {
      $context = 'Movie: ' . ($video->movie->name ?? 'Unknown');
    } elseif ($video->movie_trailers_id) {
      $context = 'Movie Trailer: ' . ($video->movieTrailer->name ?? 'Unknown');
    }

    // Construct the email message
    $message = "A new video has been uploaded by {$userName}.\n\n" .
        "Video Details:\n" .
        "ID: {$video->id}\n" .
        "File Name: {$video->file_name}\n" .
        "Type: {$video->type}\n" .
        "Size: {$video->size} bytes\n" .
        "Storage Location: {$video->storage_location}\n" .
        "Upload Status: {$video->upload_status}\n" .
        ($context ? "Context: {$context}\n" : '');

    $subject = "New Video Uploaded";

    try {
      SendAdminNotification::dispatch($subject, $message);
    } catch (Exception $e) {
      $errorDetails = $e->getMessage();
      $errorSubject = "Error Notification";
      $errorMessage = "An error occurred: " . $errorDetails;
      $adminEmail = env('ADMIN_EMAIL', 'default_admin@example.com'); // Set a default value as a fallback
      Mail::to($adminEmail)->send(new AdminNotification($errorSubject, $errorMessage));
    }
  }
}