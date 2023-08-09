<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;
use App\Events\NewChatTestMessage;


class TestMessageController extends Controller
{
    public function broadcast(HttpRequest $request) {

        if (! $request->filled('message')) {
            return response()->json([
                'message' => 'No message to send'
            ], 422);
        }

        // TODO: Sanitize input

        event(new NewChatTestMessage($request->message));
        return response()->json(['success'], 201);

    }
}
