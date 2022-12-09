<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Events\Message;
use Illuminate\Http\Request as HttpRequest;
use Inertia\Inertia;
use Illuminate\Support\Carbon;
use App\Events\NewChatMessage;
//use function Termwind\render;


class ChatController extends Controller
{

    public function channels( HttpRequest $request ){
        return Channel::all();
    }
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function messages( HttpRequest $request, $channelId ) {

         return ChatMessage::query()
            ->where('channel_id', $channelId)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->with(['user' => function ($query) {
                $query->select('id', 'name', 'profile_photo_path');
            }])
            ->latest()
            ->limit(0)
            ->orderBy('created_at', 'DESC')
            ->get();

    }

    public function index()
    {
        return Inertia::render('Chat');
//        return view('chat');

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

    public function newMessage(HttpRequest $request)
    {
        $newMessage = new ChatMessage;
        $newMessage->user_id = Auth::id();
        $newMessage->channel_id = $request->channel_id;
        $newMessage->message = $request->message;
        $newMessage->user_name = $request->user_name;
        $newMessage->user_profile_photo_path = $request->user_profile_photo_path;
        $newMessage->user_profile_photo_url = $request->user_profile_photo_url;
        $newMessage->save();
//
//        $broadcastMessage = $newMessage;
//        $broadcastMessage->user_profile_pic =

        broadcast(new NewChatMessage( $newMessage ))->toOthers();
//        broadcast(new NewChatMessage($request->input('username'), $request->input('message')));
        return $newMessage;

    }

//    public function message(Request $request)
//    {
//        event(new Message($request->input('username'), $request->input('message')));
//
//            return ['message'];
//    }
}
