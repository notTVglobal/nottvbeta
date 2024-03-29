<?php

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

// This channel is for viewers being added/removed from a channel for the viewer count.
//Broadcast::channel('viewerCount', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});


//Broadcast::channel('chat.{channelId}', function () {
//    if( Auth::check() ) {
//        return true;
//    }
//});

