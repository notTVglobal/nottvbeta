<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Carbon;
use App\Events\NewChatMessage;


class ChatController extends Controller
{

    public function channels(HttpRequest $request): \Illuminate\Database\Eloquent\Collection
    {
        return Channel::all();
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function messages(HttpRequest $request, $channelId): \Illuminate\Database\Eloquent\Collection|array
    {
        return ChatMessage::query()
            ->where('channel_id', $channelId)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->with(['user' => function ($query) {
                $query->select('id', 'name', 'profile_photo_path');
            }])
            ->latest()
            ->limit(20)
            ->get();
    }

    public function newMessage(HttpRequest $request): \Illuminate\Http\JsonResponse
    {
        if (!$request->filled('message')) {
            return response()->json([
                'message' => 'No message to send'
            ], 422);
        }

        // TODO: Sanitize input
        $request->message = strip_tags($request->input('message'));
        $url = '@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
        $request->message = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0" class="text-blue-400 hover:text-blue-600">$0</a>', $request->message);

        $chatMessage = new ChatMessage;

        $chatMessage->user_id = Auth::id();
        $chatMessage->channel_id = $request->channel_id;
        $chatMessage->message = $request->message;
        $chatMessage->user_name = $request->user_name;
        $chatMessage->user_profile_photo_path = $request->user_profile_photo_path;
        $chatMessage->user_profile_photo_url = $request->user_profile_photo_url;
        event(new NewChatMessage($chatMessage));
        $chatMessage->save();

        return response()->json(['success'], 201);

    }
}
