<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Events\Message;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Events\NewChatMessage;


class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return Inertia::render('Chat');
    }

    public function fetchMessages(Request $request)
    {
        // this needs to be protected -> only return the user.name and profile_photo_path.
        return ChatMessage::query()
            ->with('user')
            ->orderBy('created_at', 'DESC')
            ->limit(50)
            ->get();
    }

    public function newMessage(Request $request)
    {
        $newMessage = new ChatMessage;
        $newMessage->user_id = Auth::id();
        $newMessage->message = $request->message;
        $newMessage->save();

        broadcast(new NewChatMessage( $newMessage ))->toOthers();

        return $newMessage;
    }

//    public function message(Request $request)
//    {
//        event(new Message($request->input('username'), $request->input('message')));
//
//            return ['message'];
//    }
}
