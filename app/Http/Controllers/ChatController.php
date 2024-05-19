<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Carbon;
use App\Events\NewChatMessage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;


class ChatController extends Controller {

  public function channels(HttpRequest $request): \Illuminate\Database\Eloquent\Collection {
    return Channel::all();
  }

  public function __construct() {
    $this->middleware('auth');
  }

  public function messages(HttpRequest $request, $channelId): \Illuminate\Database\Eloquent\Collection|array {
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

  public function newMessage(HttpRequest $request): \Illuminate\Http\JsonResponse {
    $validator = Validator::make($request->all(), [
        'message'                 => 'required|string|max:300', // Adjust max length as needed
        'channel_id'              => 'required|exists:channels,id', // Ensure the channel exists
      // Add validation for other fields if necessary
        'user_name'               => 'required|string',
        'user_profile_photo_path' => 'nullable|string',
        'user_profile_photo_url'  => 'nullable|string',
    ]);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 422);
    }

    $validated = $validator->validated();

    if (Gate::denies('send-chat-message', $validated['channel_id'])) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }

    // First, escape the message to prevent XSS attacks
    $escapedMessage = e($validated['message']);

    // Use preg_replace_callback to format URLs in the escaped message
    $formattedMessage = preg_replace_callback(
        '#\b(?:https?://|http?://|www\.)[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))|\b[a-zA-Z0-9.-]+\.(?:com|tv|ca)\b#',
        function ($matches) {
          $url = $matches[0];
          // Prepend https:// if the URL does not have a protocol
          if (!preg_match('~^(?:f|ht)tps?://~i', $url)) {
            $url = 'https://' . $url;
            $displayText = substr($url, 8); // Remove 'https://' for display text
          } else {
            $displayText = $url;
          }

          // Construct the anchor tag with the appropriate attributes and styles
          return '<a href="' . $url . '" target="_blank" rel="noopener noreferrer" title="' . $url . '" style="color: #3B82F6; transition: color 0.2s ease-in-out;" onmouseover="this.style.color = \'#F59E0B\'" onmouseout="this.style.color = \'#3B82F6\'">' . $displayText . '</a>';
        },
        $escapedMessage
    );

    // Then, detect URLs in the escaped message and format them as clickable links
//    $formattedMessage = preg_replace_callback(
//        "#\b(https?://)?([^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#",
//        function ($matches) {
//          // Prepend http:// if the URL does not have a protocol
//          $url = $matches[1] ? $matches[0] : "http://" . $matches[0];
//          return '<a href="' . $url . '" target="_blank" rel="noopener noreferrer" title="' . $url . '" style="color: #3B82F6; /* Equivalent to text-blue-100 */
//        transition: color 0.2s ease-in-out;"
//        onmouseover="this.style.color = \'#F59E0B\'"
//        onmouseout="this.style.color = \'#3B82F6\'">' . $url . '</a>';
//        },
//        $escapedMessage
//    );


    $chatMessage = new ChatMessage([
        'user_id'                 => Auth::id(),
        'channel_id'              => $validated['channel_id'],
        'message'                 => $formattedMessage, // Use the formatted message
        'user_name'               => $validated['user_name'],
        'user_profile_photo_path' => $validated['user_profile_photo_path'],
        'user_profile_photo_url'  => e($validated['user_profile_photo_url']),
    ]);

    // Save first, then dispatch event
    if ($chatMessage->save()) {
      event(new NewChatMessage($chatMessage));

      return response()->json(['message' => 'Message sent successfully'], 201);
    }

    return response()->json(['message' => 'Failed to send message'], 500);
  }


//    public function newMessage(HttpRequest $request): \Illuminate\Http\JsonResponse
//    {
//
//
//
//      $validator = Validator::make($request->all(), [
//          'message' => 'required|string|max:300', // Adjust max length as needed
//          'channel_id' => 'required|exists:channels,id', // Ensure the channel exists
//        // Add validation for other fields if necessary
//          'user_name' => 'required|string',
//          'user_profile_photo_path' => 'nullable|string',
////          'user_profile_photo_url' => 'nullable|string',
//      ]);
//
//      if ($validator->fails()) {
//        return response()->json(['errors' => $validator->errors()], 422);
//      }
//
//      $validated = $validator->validated();
//
//      if (Gate::denies('send-chat-message', $validated['channel_id'])) {
//        return response()->json(['message' => 'Unauthorized'], 403);
//      }
//
//      $chatMessage = new ChatMessage([
//          'user_id' => Auth::id(),
//          'channel_id' => $validated['channel_id'],
//          'message' => e($validated['message']), // Use HTML entity encoding
//          'user_name' => ($validated['user_name']),
//          'user_profile_photo_path' => ($validated['user_profile_photo_path']),
////          'user_profile_photo_url' => e($validated['user_profile_photo_url']),
//      ]);
//
//      // Save first, then dispatch event
//      if ($chatMessage->save()) {
//        event(new NewChatMessage($chatMessage));
//        return response()->json(['message' => 'Message sent successfully'], 201);
//      }
//
//      return response()->json(['message' => 'Failed to send message'], 500);
//
//    }
}
