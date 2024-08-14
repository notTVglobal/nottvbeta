<?php

namespace App\Services;

use App\Events\NewNotificationEvent;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Exception;

class NotificationService
{
  public static function createAndDispatchNotification($userId, $imageId, $url, $title, $message): void {
    // Log all the parameters
//    Log::debug('Creating notification with data: ', [
//        'user_id' => $userId,
//        'image_id' => $imageId,
//        'url' => $url,
//        'title' => $title,
//        'message' => $message
//    ]);

    try {
    $notification = new Notification;
    $notification->user_id = $userId;
    $notification->image_id = $imageId;
    $notification->url = $url;
    $notification->title = $title;
    $notification->message = $message;
    $notification->save();

    event(new NewNotificationEvent($notification));
    } catch (Exception $e) {
      // Log the exception
      Log::error('NotificationService encountered an error: ' . $e->getMessage());

      // Additionally, you might want to handle the error in a way that is appropriate
      // for your application, such as sending a response, notifying an admin, etc.
    }
  }
}
