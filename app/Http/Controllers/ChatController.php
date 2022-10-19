<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Events\Message;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Events\NewChatMessage;


class ChatController extends Controller
{

    public function channels( Request $request ){
        return Channel::all();
    }
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function messages( Request $request, $channelId ) {
        // this needs to be protected -> only return the user.name and profile_photo_path.
        return ChatMessage::where('channel_id', $channelId)
            ->with('user')
            ->orderBy('created_at', 'DESC')
            ->limit(50)
            ->get();
    }

    public function index()
    {
        return Inertia::render('Chat');
    }

//    public function fetchMessages(Request $request)
//    {
//        // this needs to be protected -> only return the user.name and profile_photo_path.
//        return ChatMessage::query()
//            ->with('user')
//            ->orderBy('created_at', 'DESC')
//            ->limit(50)
//            ->get();
//    }

    public function newMessage(Request $request, $channelId)
    {
        $newMessage = new ChatMessage;
        $newMessage->user_id = Auth::id();
//        $newMessage->channel_id = $channelId;
        $newMessage->channel_id = $request->channel_id;
        $newMessage->message = $request->message;
        $newMessage->save();

        broadcast(new NewChatMessage( $newMessage ))->toOthers();
        return $newMessage->toJson('ok');
    }

//    public function message(Request $request)
//    {
//        event(new Message($request->input('username'), $request->input('message')));
//
//            return ['message'];
//    }
}
