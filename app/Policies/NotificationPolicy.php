<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the notification.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Notification  $notification
     * @return bool
     */
    public function delete(User $user, Notification $notification)
    {
        // Here, you can define your logic to check if the user can delete the notification.
        // For example, you can check if the user owns the notification or has admin privileges.
        // Return true if the user can delete, false otherwise.

        // Example: Allow the user to delete their own notifications
//        return $user->id === $notification->user_id;
        return $user->id === $notification->user_id;
    }
}
