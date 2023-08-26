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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

//Broadcast::channel('chat.{channelId}', function ($user, $channelId) {
//        if( Auth::check() ) {
//            return ['id' => $user->id, 'name' => $user->name];
//        }
//});


//Broadcast::channel('chat.{channelId}', function (User $user) {
//    return Auth::check();
//});

// This channel is for the chat.
Broadcast::channel('chat.{id}', function ($user, $id) {
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

