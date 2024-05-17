<?php

use App\Models\Movie;
use App\Models\MovieTrailer;
use App\Models\NewsStory;
use App\Models\OtherContent;
use App\Models\Show;
use App\Models\ShowEpisode;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// A channel specifically for each user.
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// This channel is for MistServer Updates like receiving new Push information.
Broadcast::channel('mistServerUpdate.{streamName}', function ($user) {
  if( Auth::check() ) {
    return true;
  }
  return !is_null($user->creator);
});

// This channel is for the chat.
Broadcast::channel('chat.{id}', function ($user, $id) {
        if( Auth::check() ) {
        return true;
    }
        return false;
});

// This channel is for the viewer presence.
Broadcast::channel('viewerCount.{id}', function ($user, $id) {
    if( Auth::check() ) {
        return true;
    }
    return false;
});

// This channel is for user notifications.
Broadcast::channel('user.{id}', function ($user, $id) {
    if( Auth::check() ) {
        return true;
    }
    return false;
});

// This channel is for receiving notifications related to a specific
// model, for Creators.... such as on the Show Manage page, to
// broadcast when the Schedule is being updated.
Broadcast::channel('creator.{type}.{id}', function (User $user, $type, $id) {
  $modelClass = getModelClass($type);
  $model = $modelClass::find($id);

  // TODO: The other model policies will need a manage() if they don't already
  //  because we use it in our routes\channels.php for broadcasting events.
  //  See: ShowPolicy as an example.
  if ($model && $user->can('manage', $model)) {
    return ['id' => $user->id, 'name' => $user->name];
  }

  return false;
});

function getModelClass($type): string {
  return match ($type) {
    'show' => Show::class,
    'movie' => Movie::class,
    'movieTrailer' => MovieTrailer::class,
    'showEpisode' => ShowEpisode::class,
    'newsStory' => NewsStory::class,
    'otherContent' => OtherContent::class,
    default => throw new \InvalidArgumentException("Invalid content type: $type"),
  };
}

// This channel is for viewers being added/removed from a channel for the viewer count.
//Broadcast::channel('viewerCount', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});


//Broadcast::channel('chat.{channelId}', function () {
//    if( Auth::check() ) {
//        return true;
//    }
//});

