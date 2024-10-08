<?php

namespace App\Http\Controllers;

use App\Events\ReactionUpdated;
use App\Models\Channel;
use App\Models\ChatMessage;
use App\Models\ChatMessageReaction;
use App\Traits\EmojiConversion;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Carbon;
use App\Events\NewChatMessage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class ChatController extends Controller {

  use EmojiConversion;

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
        ->with([
            'user'      => function ($query) {
              $query->select('id', 'name', 'profile_photo_path', 'is_banned', 'ban_expires_at');
            },
            'reactions' => function ($query) {
              $query->select('chat_message_id', 'user_id', 'reaction_type');
            }
        ])
        ->latest()
        ->limit(20)
        ->get()
        ->each(function ($message) {
          $message->message = Crypt::decryptString($message->message);
          $message->user_name = Crypt::decryptString($message->user_name);

          // Handle null reactions
          if (is_null($message->reactions)) {
            $message->reactions = [];
          }
        });
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

    $user = Auth::user();
    $isVisible = true;

    // Check if the user is shadow banned
    if ($user->is_banned) {
      if ($user->ban_expires_at && Carbon::now()->greaterThan($user->ban_expires_at)) {
        // Ban has expired, lift the ban
        $user->is_banned = false;
        $user->ban_expires_at = null;
        $user->save();
      } else {
        // User is still banned
        $isVisible = false;
      }
    }

    // Convert certain letter combinations into emojis
    $formattedMessage = $this->convertToEmojis($validated['message']);

    // First, escape the message to prevent XSS attacks
    $escapedMessage = e($formattedMessage);
//    $escapedMessage = e($validated['message']);

    // Use preg_replace_callback to format URLs in the escaped message
    $escapedMessageWithUrls = preg_replace_callback(
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

    $chatMessage = new ChatMessage([
        'user_id'                 => Auth::id(),
        'channel_id'              => $validated['channel_id'],
        'message'                 => $escapedMessageWithUrls,
        'user_name'               => $validated['user_name'],
        'user_profile_photo_path' => $validated['user_profile_photo_path'],
        'user_profile_photo_url'  => e($validated['user_profile_photo_url']),
        'is_visible'              => $isVisible, // Set visibility based on ban status
    ]);

    // Dispatch first, then save.
    event(new NewChatMessage($chatMessage));

    // Encrypt the message and username before saving
    $chatMessage->message = Crypt::encryptString($escapedMessageWithUrls);
    $chatMessage->user_name = Crypt::encryptString($validated['user_name']);

    if ($chatMessage->save()) {

      return response()->json(['message' => 'Message sent successfully'], 201);
    }

    return response()->json(['message' => 'Failed to send message'], 500);
  }

  public function toggleReaction(HttpRequest $request): \Illuminate\Http\JsonResponse {
    try {
      // Validate the incoming request
      $validated = $request->validate([
          'message_id'    => 'required|exists:chat_messages,id',
          'reaction_type' => 'required|in:heart,thumbs_up',
      ]);

      // Attempt to find the chat message
      $chatMessage = ChatMessage::findOrFail($validated['message_id']);
      $userId = auth()->id();

      if (!$userId) {
        // Handle the case where the user is not authenticated
        return response()->json(['error' => 'User not authenticated'], 401);
      }

      // Check if the user has already reacted
      $existingReaction = ChatMessageReaction::where('chat_message_id', $validated['message_id'])
          ->where('user_id', $userId)
          ->first();

      if ($existingReaction) {
        // If the same reaction type, remove it
        if ($existingReaction->reaction_type === $validated['reaction_type']) {
          $existingReaction->delete();
        } else {
          // Otherwise, change the reaction type
          $existingReaction->update(['reaction_type' => $validated['reaction_type']]);
        }
      } else {
        // Create a new reaction
        ChatMessageReaction::create([
            'chat_message_id' => $validated['message_id'],
            'user_id'         => $userId,
            'reaction_type'   => $validated['reaction_type'],
        ]);
      }

      // Broadcast the update
      broadcast(new ReactionUpdated($chatMessage->id, $chatMessage->channel_id, $validated['reaction_type'], $userId))->toOthers();

      return response()->json(['message' => 'Reaction updated successfully']);

    } catch (ValidationException $e) {
      // Handle validation exceptions
      return response()->json(['error' => 'Validation failed', 'details' => $e->errors()], 422);
    } catch (ModelNotFoundException $e) {
      // Handle cases where the chat message is not found
      return response()->json(['error' => 'Chat message not found'], 404);
    } catch (Exception $e) {
      // Log any other exceptions
      Log::error('Error toggling reaction: ' . $e->getMessage(), ['exception' => $e]);

      return response()->json(['error' => 'An unexpected error occurred. Please try again later.'], 500);
    }
  }
}
