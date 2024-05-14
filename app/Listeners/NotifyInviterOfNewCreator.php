<?php

namespace App\Listeners;

use App\Events\CreatorRegistrationCompleted;
use App\Models\InviteCode;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyInviterOfNewCreator
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
   * @param  CreatorRegistrationCompleted  $event
   * @return void
   */
  public function handle(CreatorRegistrationCompleted $event): void {

    // Get the inviter using the relationship
    $inviter = $event->inviteCode->createdBy;

      if ($inviter) {
        $title = 'Your Invite Was Used!';
        $message = "A new creator, {$event->user->name}, has registered using your invite code.";

        NotificationService::createAndDispatchNotification(
            $inviter->id,
            null, // Assuming no specific image ID is needed
            null, // '/profile/' . $event->user->id, // Adjust the URL to your profile route
            $title,
            $message
        );
      }
    }
}
