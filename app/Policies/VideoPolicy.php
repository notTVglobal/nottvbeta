<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class VideoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return Auth::user()->isAdmin;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Video $video)
    {
//        return Auth::user()->isAdmin;
        if ($user->isAdmin) {
            return true;
        } return $user->creator && $video->user->id === $user->id;
        // If a video becomes restricted a user
        // will not be able to view the video.
        // Also, some users may not be able to access
        // a video if it is under review.
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
  public function create(User $user)
  {
    return $user->isCreator()
        ? Response::allow()
        : Response::deny('Only creators can create videos.');
  }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Video $video)
    {
        return Auth::user()->isAdmin;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Auth\Access\Response|bool
     */
  public function delete(User $user, Video $video)
  {

    // Check if the user is an admin
    if ($user->isAdmin) {
      return Response::allow();
    }

    // Check if the video belongs to the user
    if ($video->user_id !== $user->id) {
      return Response::deny('You do not own this video.');
    }

    // Check if the video is attached to any other object
    $attachments = $video->attachments(); // Assuming this method returns the related models

    if ($attachments->isNotEmpty()) {
      $attachment = $attachments->first();
      $modelType = class_basename($attachment);
      $modelName = $attachment->Name ?? 'Unnamed';

      return Response::deny("This video is attached to a $modelType with the name '$modelName'. Please detach the video first before deleting.");
    }

    // If all checks pass, allow the deletion
    return Response::allow();
  }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Video $video)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Video $video)
    {
        //
    }
}
